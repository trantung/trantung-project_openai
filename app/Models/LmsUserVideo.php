<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsUserVideo extends Model
{
    use HasFactory;

    protected $table = "lms_user_video";
    public $fillable = ['user_id', 'course_id', 'video_id', 'status'];
    public $timestamps = true;
}
