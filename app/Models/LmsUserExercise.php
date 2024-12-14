<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsUserExercise extends Model
{
    use HasFactory;

    protected $table = "lms_user_exercise";
    public $fillable = ['user_id', 'course_id', 'video_id', 'status', 'exercise_id'];
    public $timestamps = true;
}
