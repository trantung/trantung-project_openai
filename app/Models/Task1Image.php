<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task1Image extends Model
{
    use HasFactory;
    protected $table = "task1_images";
    public $fillable = ['user_id', 'username', 'topic', 'question', 'status', 'image_base64', 'image_response'];
    public $timestamps = true;

    const STATUS_SUCCESS = 1;
    const STATUS_NOT_SUCCESS = 0;
    const TASK_2 = 2;
    const TASK_1 = 1;
}
