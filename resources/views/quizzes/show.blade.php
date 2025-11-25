@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>📝 {{ $quiz->title }}</h4>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $quiz->description }}</p>
                    
                    <div class="quiz-details mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>📊 Total Questions:</strong> {{ $quiz->questions->count() }}</p>
                                <p><strong>⏱️ Time Limit:</strong> {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>🎯 Passing Score:</strong> {{ $quiz->passing_score }}%</p>
                                <p><strong>📈 Total Points:</strong> {{ $quiz->total_points }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="questions-preview mb-4">
                        <h5>Sample Questions:</h5>
                        @foreach($quiz->questions->take(3) as $index => $question)
                        <div class="card mb-2">
                            <div class="card-body">
                                <p><strong>Q{{ $index + 1 }}:</strong> {{ $question->question_text }}</p>
                                @if($question->question_type === 'multiple_choice')
                                    <div class="options">
                                        @foreach($question->options as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" disabled>
                                                <label class="form-check-label">{{ $option }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('quizzes.start', ['course' => $course->id, 'quiz' => $quiz->id]) }}" 
                           class="btn btn-success btn-lg">Start Quiz</a>
                        <a href="{{ route('quizzes.index', $course->id) }}" class="btn btn-secondary">Back to Quizzes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<div class="mt-4">
    <h5>Course Actions</h5>
    <div class="d-flex gap-2 flex-wrap">
        @auth
            @if(auth()->user()->isStudent() || auth()->user()->isParent())
                {{-- Enrollment buttons --}}
            @endif
            
            @if(auth()->user()->isEnrolledInCourse($course->id) || auth()->user()->isInstructor() || auth()->user()->isAdmin())
                <a href="{{ route('quizzes.index', $course->id) }}" class="btn btn-info">
                    📝 Course Quizzes
                </a>
            @endif
        @endauth
    </div>
</div>