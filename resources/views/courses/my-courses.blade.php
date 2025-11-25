@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>📚 My Enrolled Courses</h2>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @forelse($enrollments as $enrollment)
@php $course = $enrollment->course; @endphp
<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title">{{ $course->title }}</h5>
            <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
            
            <div class="mb-2">
                <span class="badge bg-primary">{{ ucfirst($course->difficulty) }}</span>
                <span class="badge bg-secondary">{{ $course->category->name }}</span>
                <span class="badge bg-{{ $enrollment->status == 'active' ? 'success' : 'warning' }}">
                    {{ ucfirst($enrollment->status) }}
                </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="mb-2">
                <small>Progress: {{ $enrollment->progress_percentage }}%</small>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ $enrollment->progress_percentage }}%">
                    </div>
                </div>
            </div>
            
            <p><strong>Instructor:</strong> {{ $course->instructor->name }}</p>
            <p><strong>Enrolled:</strong> 
                {{-- Safe date format --}}
                @if($enrollment->enrolled_at)
                    {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}
                @else
                    {{ $enrollment->created_at->format('M d, Y') }}
                @endif
            </p>
            
            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">Continue Learning</a>
        </div>
    </div>
</div>
                @empty
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h5>No courses enrolled yet!</h5>
                        <p>Browse our course catalog and start your learning journey.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse Courses</a>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $enrollments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection