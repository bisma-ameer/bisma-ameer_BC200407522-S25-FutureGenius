@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📝 Quizzes - {{ $course->title }}</h2>
                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-secondary">Back to Course</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @forelse($quizzes as $quiz)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $quiz->title }}</h5>
                            <p class="card-text">{{ $quiz->description }}</p>
                            
                            <div class="quiz-info mb-3">
                                <span class="badge bg-primary">{{ $quiz->questions_count }} Questions</span>
                                @if($quiz->time_limit)
                                    <span class="badge bg-warning">{{ $quiz->time_limit }} mins</span>
                                @endif
                                <span class="badge bg-info">Passing: {{ $quiz->passing_score }}%</span>
                            </div>

                            @php
                                $userAttempt = auth()->user()->quizAttempts()
                                    ->where('quiz_id', $quiz->id)
                                    ->where('status', 'completed')
                                    ->latest()
                                    ->first();
                            @endphp

                            @if($userAttempt)
                                <div class="attempt-result mb-3">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $userAttempt->is_passed ? 'bg-success' : 'bg-danger' }}" 
                                             style="width: {{ $userAttempt->percentage }}%">
                                            {{ $userAttempt->percentage }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Attempted: {{ $userAttempt->created_at->format('M d, Y') }}
                                        @if($userAttempt->is_passed)
                                            <span class="text-success">✅ Passed</span>
                                        @else
                                            <span class="text-danger">❌ Failed</span>
                                        @endif
                                    </small>
                                </div>
                            @endif

                            <div class="d-grid gap-2">
                                @if($userAttempt)
                                    <a href="{{ route('quizzes.result', ['course' => $course->id, 'quiz' => $quiz->id, 'attempt' => $userAttempt->id]) }}" 
                                       class="btn btn-outline-primary">View Result</a>
                                    <a href="{{ route('quizzes.start', ['course' => $course->id, 'quiz' => $quiz->id]) }}" 
                                       class="btn btn-warning">Retake Quiz</a>
                                @else
                                    <a href="{{ route('quizzes.start', ['course' => $course->id, 'quiz' => $quiz->id]) }}" 
                                       class="btn btn-success">Start Quiz</a>
                                @endif
                                <a href="{{ route('quizzes.show', ['course' => $course->id, 'quiz' => $quiz->id]) }}" 
                                   class="btn btn-outline-secondary">Preview</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h5>No quizzes available yet!</h5>
                        <p>Check back later for quizzes related to this course.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection