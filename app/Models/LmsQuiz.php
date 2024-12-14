<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsQuiz extends Model
{
    use HasFactory;

    protected $table = "lms_quiz";
    public $fillable = ['course_id', 'quiz_id', 'section_id'];
    public $timestamps = true;
}
