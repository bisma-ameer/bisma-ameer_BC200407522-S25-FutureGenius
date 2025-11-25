@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header {{ $attempt->is_passed ? 'bg-success' : 'bg-danger' }} text-white">
                    <h4>📊 Quiz Result - {{ $attempt->quiz->title }}</h4>
                </div>
                <div class="card-body text-center">
                    @if($attempt->is_passed)
                        <div class="result-success mb-4">
                            <i class="fas fa-trophy fa-5x text-success mb-3"></i>
                            <h3 class="text-success">🎉 Congratulations! You Passed! 🎉</h3>
                        </div>
                    @else
                        <div class="result-failure mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger mb-3"></i>
                            <h3 class="text-danger">😔 Keep Trying! You'll Get It Next Time!</h3>
                        </div>
                    @endif

                    <div class="score-card mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Your Score</h5>
                                        <h2 class="{{ $attempt->is_passed ? 'text-success' : 'text-danger' }}">
                                            {{ $attempt->percentage }}%
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Passing Score</h5>
                                        <h2 class="text-info">{{ $attempt->quiz->passing_score }}%</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detailed-stats mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Correct Answers:</strong> {{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Score:</strong> {{ $attempt->score }}/{{ $attempt->quiz->total_points }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Time Taken:</strong> 
                                    @if($attempt->completed_at && $attempt->started_at)
                                        {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} minutes
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('quizzes.index', $attempt->quiz->course_id) }}" class="btn btn-primary">
                            Back to Quizzes
                        </a>
                        <a href="{{ route('courses.show', $attempt->quiz->course_id) }}" class="btn btn-secondary">
                            Back to Course
                        </a>
                        @if(!$attempt->is_passed)
                            <a href="{{ route('quizzes.start', ['course' => $attempt->quiz->course_id, 'quiz' => $attempt->quiz->id]) }}" 
                               class="btn btn-warning">Retake Quiz</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection