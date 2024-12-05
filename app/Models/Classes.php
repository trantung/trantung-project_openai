<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'classes';

    protected $fillable = ['name', 'course_id', 'year', 'status'];
}
