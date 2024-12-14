<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassCourse extends Model
{
    use HasFactory, SoftDeletes;
    public $paginate = 10;
    protected $table = 'class_course';

    protected $fillable = ['course_id', 'class_id'];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(ApiMoodle::class, 'course_id');
    }
}
