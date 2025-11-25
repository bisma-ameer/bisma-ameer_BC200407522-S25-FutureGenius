<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Models\QuizAttempt;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * Routes to role-specific dashboards.
     */
    public function index()
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'instructor':
                return $this->instructorDashboard($user);
            case 'student':
                return $this->studentDashboard($user);
            case 'parent':
                return $this->parentDashboard($user);
            default:
                return view('welcome');
        }
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        $totalUsers = User::count();
        $studentCount = User::where('role', 'student')->count();
        $instructorCount = User::where('role', 'instructor')->count();
        $parentCount = User::where('role', 'parent')->count();
        $adminCount = User::where('role', 'admin')->count();

        $totalCourses = Course::count();
        $publishedCourses = Course::where('is_published', true)->count();

        $totalEnrollments = CourseEnrollment::count();
        $quizAttemptsCount = QuizAttempt::count();

        $recentUsers = User::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'studentCount',
            'instructorCount',
            'parentCount',
            'adminCount',
            'totalCourses',
            'publishedCourses',
            'totalEnrollments',
            'quizAttemptsCount',
            'recentUsers'
        ));
    }

    /**
     * Instructor Dashboard
     */
    private function instructorDashboard(User $user)
    {
        // Fetch instructor courses and counts
        $myCourses = Course::where('instructor_id', $user->id)
            ->withCount('enrollments') // requires Course::enrollments() relation
            ->latest('created_at')
            ->get();

        $myCoursesCount = $myCourses->count();

        // Recent courses (limit)
        $recentCourses = $myCourses->take(5);

        // If no courses, safe empty array for whereIn
        $courseIds = $myCourses->pluck('id')->toArray();
        if (empty($courseIds)) {
            $totalStudents = 0;
            $recentEnrollments = collect();
        } else {
            // total unique students across instructor's courses
            $totalStudents = CourseEnrollment::whereIn('course_id', $courseIds)
                ->distinct('student_id')
                ->count('student_id');

            // recent enrollments (limit 5) with minimal eager loading
            $recentEnrollments = CourseEnrollment::with([
                    'student:id,name,email',
                    'course:id,title'
                ])
                ->whereIn('course_id', $courseIds)
                ->latest('enrolled_at')
                ->take(5)
                ->get();
        }

        // Placeholders for features not implemented yet
        $assignmentsCount = 0;
        $upcomingAssignments = collect();
        $materialsCount = 0;

        // Chart data
        $courseTitles = $recentCourses->pluck('title')->toArray();
        $courseEnrollments = $recentCourses->pluck('enrollments_count')->toArray();

        return view('instructor.dashboard', compact(
            'myCourses',
            'myCoursesCount',
            'recentCourses',
            'totalStudents',
            'recentEnrollments',
            'assignmentsCount',
            'upcomingAssignments',
            'materialsCount',
            'courseTitles',
            'courseEnrollments'
        ));
    }

    /**
     * Student Dashboard
     */
    private function studentDashboard(User $user)
    {
        // Enrolled courses with minimal course data
        $enrolledCourses = CourseEnrollment::with(['course:id,title,thumbnail,category_id'])
            ->where('student_id', $user->id)
            ->get();

        $enrolledCoursesCount = $enrolledCourses->count();

        $completedQuizzesCount = QuizAttempt::where('student_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $totalProgress = $enrolledCourses->sum('progress_percentage');
        $overallProgress = $enrolledCoursesCount > 0 ? round($totalProgress / $enrolledCoursesCount) : 0;

        $certificatesCount = $enrolledCourses->where('progress_percentage', '>=', 80)->count();

        $recentQuizAttempts = QuizAttempt::with(['quiz:id,title,course_id', 'quiz.course:id,title'])
            ->where('student_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $parentInfo = $user->parent ?? null;

        $courseTitles = $enrolledCourses->pluck('course.title')->toArray();
        $courseProgress = $enrolledCourses->pluck('progress_percentage')->toArray();

        return view('student.dashboard', compact(
            'enrolledCourses',
            'enrolledCoursesCount',
            'completedQuizzesCount',
            'overallProgress',
            'certificatesCount',
            'recentQuizAttempts',
            'parentInfo',
            'courseTitles',
            'courseProgress'
        ));
    }

    /**
     * Parent Dashboard
     */
    private function parentDashboard(User $user)
    {
        $children = User::where('parent_id', $user->id)->get();

        $recentActivities = $this->getChildrenRecentActivities($user->id);

        $childrenNames = $children->pluck('name')->toArray();
        $childrenProgress = $children->map(function ($child) {
            $enrollments = CourseEnrollment::where('student_id', $child->id)->get();
            return $enrollments->avg('progress_percentage') ?? 0;
        })->toArray();

        return view('parent.dashboard', compact(
            'children',
            'recentActivities',
            'childrenNames',
            'childrenProgress'
        ));
    }

    /**
     * Recent activities for parent's children
     */
    private function getChildrenRecentActivities(int $parentId)
    {
        $children = User::where('parent_id', $parentId)->get();
        $activities = collect();

        foreach ($children as $child) {
            // quiz attempts
            $quizAttempts = QuizAttempt::with(['quiz:id,title,course_id', 'quiz.course:id,title'])
                ->where('student_id', $child->id)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($attempt) use ($child) {
                    return (object) [
                        'child_name'   => $child->name,
                        'type'         => 'quiz',
                        'course_title' => $attempt->quiz->course->title ?? 'N/A',
                        'score'        => $attempt->percentage ?? null,
                        'created_at'   => $attempt->created_at,
                    ];
                });

            // recent enrollments (last 7 days)
            $newEnrollments = CourseEnrollment::with('course:id,title')
                ->where('student_id', $child->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->get()
                ->map(function ($enrollment) use ($child) {
                    return (object) [
                        'child_name'   => $child->name,
                        'type'         => 'enrollment',
                        'course_title' => $enrollment->course->title ?? 'N/A',
                        'score'        => null,
                        'created_at'   => $enrollment->created_at,
                    ];
                });

            $activities = $activities->merge($quizAttempts)->merge($newEnrollments);
        }

        return $activities->sortByDesc('created_at')->take(10);
    }

    /**
     * Child progress details for parent
     */
    public function childProgress($childId)
    {
        $user = auth()->user();

        $child = User::where('id', $childId)
            ->where('parent_id', $user->id)
            ->firstOrFail();

        $enrollments = CourseEnrollment::with('course:id,title,thumbnail')
            ->where('student_id', $childId)
            ->get();

        $quizAttempts = QuizAttempt::with(['quiz:id,title,course_id', 'quiz.course:id,title'])
            ->where('student_id', $childId)
            ->orderBy('created_at', 'desc')
            ->get();

        $overallProgress = $enrollments->avg('progress_percentage') ?? 0;

        return view('parent.child-progress', compact(
            'child',
            'enrollments',
            'quizAttempts',
            'overallProgress'
        ));
    }

    /**
     * Generate certificate for student
     */
    public function generateCertificate($courseId)
    {
        $user = auth()->user();

        $enrollment = CourseEnrollment::with('course:id,title')
            ->where('student_id', $user->id)
            ->where('course_id', $courseId)
            ->firstOrFail();

        if ($enrollment->progress_percentage < 80) {
            return redirect()->back()->with('error', 'Course not completed yet! Minimum 80% progress required for certificate.');
        }

        return view('certificates.course-certificate', compact('enrollment'));
    }
}
