<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory, SoftDeletes;
    public $paginate = 10;
    protected $table = 'classes';

    protected $fillable = ['name', 'year', 'status'];

    public function courses()
    {
        return $this->belongsToMany(ApiMoodle::class, 'class_course', 'class_id', 'course_id');
    }

    public function teachers()
    {
        return $this->hasManyThrough(
            Teachers::class,
            CourseTeacher::class,
            'class_id', // Foreign key on course_teacher table
            'id', // Foreign key on teachers table
            'id', // Local key on classes table
            'teacher_id' // Local key on course_teacher table
        );
    }

    public function getCourseNameAttribute()
    {
        return $this->courses->pluck('moodle_name')->implode(', ');
    }

    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
