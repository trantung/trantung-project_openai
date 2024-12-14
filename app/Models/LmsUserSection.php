<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsUserSection extends Model
{
    use HasFactory;

    protected $table = "lms_user_section";
    public $fillable = ['user_id', 'course_id', 'section_id', 'status'];
    public $timestamps = true;
}
