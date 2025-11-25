@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>📝 {{ $attempt->quiz->title }}</h4>
                        <div id="quiz-timer" class="badge bg-warning fs-6"></div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6>Quiz Instructions</h6>
                        <ul class="mb-0">
                            <li>Answer all questions to the best of your ability</li>
                            @if($attempt->quiz->time_limit)
                                <li>Time limit: {{ $attempt->quiz->time_limit }} minutes</li>
                            @endif
                            <li>You cannot go back after submitting</li>
                        </ul>
                    </div>

                    <form id="quiz-form" action="{{ route('quizzes.submit', [
                        'course' => $attempt->quiz->course_id, 
                        'quiz' => $attempt->quiz->id, 
                        'attempt' => $attempt->id
                    ]) }}" method="POST">
                        @csrf
                        
                        @foreach($attempt->quiz->questions as $index => $question)
                        <div class="question-card mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Question {{ $index + 1 }} ({{ $question->points }} point{{ $question->points > 1 ? 's' : '' }})</h5>
                                </div>
                                <div class="card-body">
                                    <p class="question-text">{{ $question->question_text }}</p>
                                    
                                    @if($question->question_type === 'multiple_choice')
                                        <div class="options">
                                            @foreach($question->options as $option)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" 
                                                           name="answers[{{ $question->id }}]" 
                                                           id="q{{ $question->id }}_option{{ $loop->index }}" 
                                                           value="{{ $option }}">
                                                    <label class="form-check-label" for="q{{ $question->id }}_option{{ $loop->index }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->question_type === 'true_false')
                                        <div class="options">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" 
                                                       name="answers[{{ $question->id }}]" 
                                                       id="q{{ $question->id }}_true" value="true">
                                                <label class="form-check-label" for="q{{ $question->id }}_true">True</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" 
                                                       name="answers[{{ $question->id }}]" 
                                                       id="q{{ $question->id }}_false" value="false">
                                                <label class="form-check-label" for="q{{ $question->id }}_false">False</label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <textarea class="form-control" 
                                                      name="answers[{{ $question->id }}]" 
                                                      rows="3" 
                                                      placeholder="Type your answer here..."></textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to submit the quiz?')">
                                Submit Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($attempt->quiz->time_limit)
<script>
    // Timer functionality
    const timeLimit = {{ $attempt->quiz->time_limit * 60 }}; // Convert to seconds
    let timeLeft = timeLimit;
    
    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('quiz-timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            document.getElementById('quiz-form').submit();
        } else {
            timeLeft--;
            setTimeout(updateTimer, 1000);
        }
    }
    
    updateTimer();
</script>
@endif
@endsection