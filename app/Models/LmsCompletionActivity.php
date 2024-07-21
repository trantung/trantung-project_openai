<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsCompletionActivity extends Model
{
    use HasFactory;
    
    protected $table = "lms_completion_activities";
    public $fillable = ['username', 'user_id', 'course_id', 'section_id', 'video_id', 'status'];
    public $timestamps = true;
}
