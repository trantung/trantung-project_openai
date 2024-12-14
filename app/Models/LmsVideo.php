<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsVideo extends Model
{
    use HasFactory;

    protected $table = "lms_video";
    public $fillable = ['course_id', 'video_id', 'section_id'];
    public $timestamps = true;

}
