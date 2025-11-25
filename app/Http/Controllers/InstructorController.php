<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Log;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('instructor');
    }

    /**
     * Show instructor courses dashboard.
     */
    public function courses(Request $request)
    {
        $user = $request->user();

        // load instructor courses with enrollment count and minimal relations for list view
        $courses = Course::where('instructor_id', $user->id)
            ->withCount(['course_enrollments as enrollments_count' => function ($q) {
                $q->select(\DB::raw("COUNT(*)"));
            }])
            ->with(['category']) // optional: eager-load category if exists on Course
            ->latest('created_at')
            ->get();

        // compatibility for views expecting $myCoursesCount
        $myCoursesCount = $courses->count();

        return view('instructor.courses', compact('courses', 'myCoursesCount'));
    }

    /**
     * List students across instructor's courses.
     */
    public function students(Request $request)
    {
        $user = $request->user();

        $enrollments = CourseEnrollment::with(['student', 'course'])
            ->whereIn('course_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('courses')
                    ->where('instructor_id', $user->id);
            })
            ->latest('enrolled_at')
            ->get();

        return view('instructor.students', compact('enrollments'));
    }

    /**
     * Assignments index placeholder.
     */
    public function assignments()
    {
        return view('instructor.assignments');
    }

    /**
     * Materials index placeholder.
     */
    public function materials()
    {
        return view('instructor.materials');
    }

    /**
     * Quick upload placeholder (file handling should be added).
     */
    public function quickUpload(Request $request)
    {
        // validate basic file input if you plan to accept files here
        // $request->validate(['file' => 'required|file|max:10240']);

        // TODO: store file, associate with course, run processing jobs, etc.
        return redirect()->back()->with('success', 'Material uploaded successfully!');
    }

    /**
     * Show students for a specific course (must belong to current instructor).
     */
    public function courseStudents(Request $request, $courseId)
    {
        $user = $request->user();

        $course = Course::where('id', $courseId)
            ->where('instructor_id', $user->id)
            ->firstOrFail();

        $enrollments = CourseEnrollment::with('student')
            ->where('course_id', $course->id)
            ->latest('enrolled_at')
            ->get();

        return view('instructor.course-students', compact('course', 'enrollments'));
    }

    /**
     * Show assignments for a specific course (placeholder).
     */
    public function courseAssignments(Request $request, $courseId)
    {
        $user = $request->user();

        $course = Course::where('id', $courseId)
            ->where('instructor_id', $user->id)
            ->firstOrFail();

        return view('instructor.course-assignments', compact('course'));
    }
}
