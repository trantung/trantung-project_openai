<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseTeacher extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'course_teacher';

    protected $fillable = ['course_id', 'teacher_id', 'class_id'];

    public function course()
    {
        return $this->belongsTo(ApiMoodle::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function scopeByCourseId($query, $course_id)
    {
        return $query->where('course_id', $course_id);
    }

    public function scopeByTeacherId($query, $teacher_id)
    {
        return $query->where('teacher_id', $teacher_id);
    }

    public function scopeByClassId($query, $class_id)
    {
        return $query->where('class_id', $class_id);
    }
}
