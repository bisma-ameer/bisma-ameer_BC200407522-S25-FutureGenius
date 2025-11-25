<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'student_id', 'status', 'progress_percentage'
    ];

    // Add these casts to fix date format issue
    protected $casts = [
        'enrolled_at' => 'datetime', // YEH LINE ADD KAREIN
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Add this relationship for quiz attempts
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id', 'student_id')
                    ->whereHas('quiz', function($query) {
                        $query->where('course_id', $this->course_id);
                    });
    }

    // Add this method to get quiz attempts count
    public function getQuizAttemptsCountAttribute()
    {
        return QuizAttempt::where('student_id', $this->student_id)
                         ->whereHas('quiz', function($query) {
                             $query->where('course_id', $this->course_id);
                         })
                         ->count();
    }
}