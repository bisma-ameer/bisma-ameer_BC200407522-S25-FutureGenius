<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title','slug','description','category_id','instructor_id','difficulty','price',
        'thumbnail','is_published','duration_hours','total_lessons'
    ];

    // existing relationships...
    public function instructor()
    {
        return $this->belongsTo(\App\Models\User::class, 'instructor_id');
    }

    // Add this enrollments() relation so withCount('enrollments') works
    public function enrollments()
    {
        return $this->hasMany(\App\Models\CourseEnrollment::class, 'course_id');
    }

    // optional helper: distinct students count via relationship
    public function students()
    {
        return $this->belongsToMany(\App\Models\User::class, 'course_enrollments', 'course_id', 'student_id')
                    ->withTimestamps();
    }
}
