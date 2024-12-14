<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsExercise extends Model
{
    use HasFactory;

    protected $table = "lms_exercise";
    public $fillable = ['course_id', 'video_id', 'exercise_id'];
    public $timestamps = true;

}
