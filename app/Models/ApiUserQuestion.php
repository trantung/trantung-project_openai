<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiUserQuestion extends Model
{
    use HasFactory;
    protected $table = "api_user_questions";
    public $fillable = ['user_id', 'username', 'topic', 'question', 'status', 'openai_response', 'prompt_token', 'total_token', 'complete_token'];
    public $timestamps = true;

    const STATUS_SUCCESS = 1;
    const STATUS_NOT_SUCCESS = 0;
}
