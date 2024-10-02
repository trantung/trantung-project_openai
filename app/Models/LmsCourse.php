<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsCourse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "lms_course";
    public $fillable = ['username', 'user_id', 'course_id'];
    public $timestamps = true;
}
