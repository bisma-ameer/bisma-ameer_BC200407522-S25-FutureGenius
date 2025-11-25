@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Instructor Dashboard</h1>
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create Course</a>
  </div>

  <div class="stat-card bg-primary text-white">
    <div class="card-body">
      <h5><i class="fas fa-book me-2"></i>My Courses</h5>
      <h2>{{ $myCoursesCount }}</h2>
      <small>Total Created</small>
    </div>
  </div>

  <div class="mt-6">
    <h3 class="text-lg font-medium mb-3">My Recent Courses</h3>
    @forelse($myCourses as $course)
      <div class="course-card bg-white p-4 rounded mb-3 shadow">
        <div class="flex items-center justify-between">
          <div>
            <div class="font-semibold text-slate-900">{{ $course->title }}</div>
            <div class="text-sm text-gray-500">Lessons: {{ $course->total_lessons }} • Duration: {{ $course->duration_hours }} hrs</div>
          </div>
          <div>
            <a href="#" class="text-blue-600">Students</a>
          </div>
        </div>
      </div>
    @empty
      <div class="text-sm text-gray-500">No courses yet — <a href="{{ route('instructor.courses.create') }}">Create a course</a></div>
    @endforelse
  </div>
</div>
@endsection
