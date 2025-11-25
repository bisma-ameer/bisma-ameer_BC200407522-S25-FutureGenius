<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'course_id','instructor_id','title','description',
        'file_path','file_original_name','file_type','due_at','is_published'
    ];

    protected $casts = ['is_published' => 'boolean', 'due_at' => 'datetime'];

    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function instructor() { return $this->belongsTo(User::class, 'instructor_id'); }
}
