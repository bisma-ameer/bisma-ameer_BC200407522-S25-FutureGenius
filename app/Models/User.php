<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is instructor
     */
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    /**
     * Check if user is student
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is parent
     */
    public function isParent()
    {
        return $this->role === 'parent';
    }

    // Add this method to User model
public function quizAttempts()
{
    return $this->hasMany(QuizAttempt::class, 'student_id');
}

// Add this method to check enrollment
public function isEnrolledInCourse($courseId)
{
    return $this->quizAttempts()
                ->whereHas('quiz', function($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->exists() || 
           \App\Models\CourseEnrollment::where([
               'course_id' => $courseId,
               'student_id' => $this->id
           ])->exists();
}
// Add this relationship to User model
public function parent()
{
    return $this->belongsTo(User::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(User::class, 'parent_id');
}

// Add this method to check if user has children
public function hasChildren()
{
    return $this->children()->exists();
}
}


