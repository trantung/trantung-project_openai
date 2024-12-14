<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentClass extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'student_class';

    protected $fillable = ['student_id', 'teacher_id', 'class_id', 'course_id'];
}
