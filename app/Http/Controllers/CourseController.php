<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('category', 'instructor')
                        ->where('is_published', true)
                        ->paginate(10);
        
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('courses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'total_lessons' => 'required|integer|min:1'
        ]);

        $course = Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'instructor_id' => auth()->id(),
            'difficulty' => $request->difficulty,
            'price' => $request->price,
            'duration_hours' => $request->duration_hours,
            'total_lessons' => $request->total_lessons,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('courses.show', $course->id)
                        ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = Course::with('category', 'instructor')->findOrFail($id);
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'total_lessons' => 'required|integer|min:1'
        ]);

        $course->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'price' => $request->price,
            'duration_hours' => $request->duration_hours,
            'total_lessons' => $request->total_lessons,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('courses.show', $course->id)
                        ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        
        return redirect()->route('courses.index')
                        ->with('success', 'Course deleted successfully!');
    }

    /**
     * Display user's enrolled courses
     */
    public function myCourses()
    {
        $user = auth()->user();
        
        if ($user->isStudent()) {
            $enrollments = CourseEnrollment::with('course')
                            ->where('student_id', $user->id)
                            ->paginate(10);
            
            return view('courses.my-courses', compact('enrollments'));
        }
        
        return redirect()->route('home');
    }

    /**
     * Enroll in a course
     */
    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();
        
        // Check if already enrolled
        $existingEnrollment = CourseEnrollment::where([
            'course_id' => $courseId,
            'student_id' => $user->id
        ])->first();
        
        if ($existingEnrollment) {
            return redirect()->route('courses.show', $courseId)
                            ->with('info', 'You are already enrolled in this course!');
        }
        
        // Create enrollment
        CourseEnrollment::create([
            'course_id' => $courseId,
            'student_id' => $user->id,
            'status' => 'active',
            'progress_percentage' => 0
        ]);
        
        return redirect()->route('courses.show', $courseId)
                        ->with('success', 'Successfully enrolled in the course!');
    }
}