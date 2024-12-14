<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsUserQuiz extends Model
{
    use HasFactory;

    protected $table = "lms_user_quiz";
    public $fillable = ['user_id', 'course_id', 'quiz_id', 'status'];
    public $timestamps = true;
}
