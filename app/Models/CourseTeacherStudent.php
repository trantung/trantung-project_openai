<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseTeacherStudent extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'course_teacher_student';

    protected $fillable = ['course_student_id', 'course_teacher_id'];
}
