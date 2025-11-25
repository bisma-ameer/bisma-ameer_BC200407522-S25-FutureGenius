@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $course->title }}</h2>
                </div>
                <div class="card-body">
                    <p class="text-muted">By {{ $course->instructor->name }} | {{ $course->category->name }} | {{ ucfirst($course->difficulty) }} Level</p>
                    
                    <p><strong>Description:</strong></p>
                    <p>{{ $course->description }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Duration:</strong> {{ $course->duration_hours }} hours</p>
                            <p><strong>Total Lessons:</strong> {{ $course->total_lessons }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Category:</strong> {{ $course->category->name }}</p>
                            <p><strong>Price:</strong> 
                                @if($course->price == 0)
                                    <span class="text-success h4">FREE</span>
                                @else
                                    <span class="text-primary h4">Rs. {{ number_format($course->price) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                   @auth
    @if(auth()->user()->isStudent() || auth()->user()->isParent())
        <div class="mt-4">
            @php
                $isEnrolled = \App\Models\CourseEnrollment::where([
                    'course_id' => $course->id,
                    'student_id' => auth()->id()
                ])->exists();
            @endphp
            
            @if($isEnrolled)
                <button class="btn btn-success btn-lg" disabled>Already Enrolled</button>
                <a href="{{ route('courses.my-courses') }}" class="btn btn-outline-primary">Go to My Courses</a>
            @else
                @if($course->price == 0)
                    <form action="{{ route('payment.free.enroll', $course->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">Enroll for FREE</button>
                    </form>
                @else
                    <a href="{{ route('payment.checkout', $course->id) }}" class="btn btn-success btn-lg"> Enroll Now - Rs. {{ number_format($course->price) }}</a>
                @endif
            @endif
        </div>
    @endif
@endauth
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Course Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Instructor:</strong> {{ $course->instructor->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Level:</strong> {{ ucfirst($course->difficulty) }}
                        </li>
                        <li class="list-group-item">
                            <strong>Category:</strong> {{ $course->category->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Duration:</strong> {{ $course->duration_hours }} hours
                        </li>
                        <li class="list-group-item">
                            <strong>Lessons:</strong> {{ $course->total_lessons }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
