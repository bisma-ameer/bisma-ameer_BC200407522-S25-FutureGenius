<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Course;
use App\Models\QuizAttempt;

class QuizController extends Controller
{
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $quizzes = Quiz::where('course_id', $courseId)
                      ->where('is_published', true)
                      ->withCount('questions')
                      ->get();
        
        return view('quizzes.index', compact('course', 'quizzes'));
    }

    public function show($courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        
        return view('quizzes.show', compact('course', 'quiz'));
    }

    public function startQuiz(Request $request, $courseId, $quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $user = auth()->user();

        // Check if already attempted
        $existingAttempt = QuizAttempt::where([
            'quiz_id' => $quizId,
            'student_id' => $user->id,
            'status' => 'in_progress'
        ])->first();

        if ($existingAttempt) {
            return redirect()->route('quizzes.attempt', [
                'course' => $courseId, 
                'quiz' => $quizId, 
                'attempt' => $existingAttempt->id
            ]);
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quizId,
            'student_id' => $user->id,
            'total_questions' => $quiz->questions->count(),
            'started_at' => now(),
            'status' => 'in_progress'
        ]);

        return redirect()->route('quizzes.attempt', [
            'course' => $courseId, 
            'quiz' => $quizId, 
            'attempt' => $attempt->id
        ]);
    }

    public function attemptQuiz($courseId, $quizId, $attemptId)
    {
        $attempt = QuizAttempt::with('quiz.questions')->findOrFail($attemptId);
        
        // Check if attempt belongs to current user
        if ($attempt->student_id != auth()->id()) {
            abort(403);
        }

        // Check if quiz is completed
        if ($attempt->status === 'completed') {
            return redirect()->route('quizzes.result', [
                'course' => $courseId, 
                'quiz' => $quizId, 
                'attempt' => $attemptId
            ]);
        }

        return view('quizzes.attempt', compact('attempt'));
    }

    public function submitQuiz(Request $request, $courseId, $quizId, $attemptId)
    {
        $attempt = QuizAttempt::with('quiz.questions')->findOrFail($attemptId);
        $answers = $request->input('answers', []);
        $score = 0;
        $correctAnswers = 0;

        // Calculate score
        foreach ($attempt->quiz->questions as $question) {
            $studentAnswer = $answers[$question->id] ?? null;
            if ($studentAnswer && $studentAnswer == $question->correct_answer) {
                $score += $question->points;
                $correctAnswers++;
            }
        }

        // Update attempt
        $attempt->update([
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'completed_at' => now(),
            'status' => 'completed',
            'answers' => $answers
        ]);

        return redirect()->route('quizzes.result', [
            'course' => $courseId, 
            'quiz' => $quizId, 
            'attempt' => $attemptId
        ]);
    }

    public function showResult($courseId, $quizId, $attemptId)
    {
        $attempt = QuizAttempt::with('quiz.questions')->findOrFail($attemptId);
        
        // Check if attempt belongs to current user
        if ($attempt->student_id != auth()->id()) {
            abort(403);
        }

        return view('quizzes.result', compact('attempt'));
    }
}