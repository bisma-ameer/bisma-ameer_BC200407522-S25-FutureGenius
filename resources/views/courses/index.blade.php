@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>All Courses</h2>
                @auth
                    @if(auth()->user()->isInstructor() || auth()->user()->isAdmin())
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">Create New Course</a>
                    @endif
                @endauth
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @forelse($courses as $course)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                            
                            <div class="mb-2">
                                <span class="badge bg-primary">{{ ucfirst($course->difficulty) }}</span>
                                <span class="badge bg-secondary">{{ $course->category->name }}</span>
                            </div>
                            
                            <p><strong>Instructor:</strong> {{ $course->instructor->name }}</p>
                            <p><strong>Duration:</strong> {{ $course->duration_hours }} hours</p>
                            <p><strong>Lessons:</strong> {{ $course->total_lessons }}</p>
                            <p><strong>Price:</strong> 
                                @if($course->price == 0)
                                    <span class="text-success">FREE</span>
                                @else
                                    Rs. {{ number_format($course->price) }}
                                @endif
                            </p>
                            
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-md-12">
                    <div class="alert alert-info">No courses available yet.</div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection