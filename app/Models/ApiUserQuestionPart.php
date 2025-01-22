<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiUserQuestionPart extends Model
{
    use HasFactory;
    protected $table = "api_user_question_parts";
    public $fillable = ['user_question_id', 'topic', 'question', 'status', 'openai_response', 'part_number', 'score', 'prompt_token', 'total_token', 'complete_token', 'writing_task_number', 'contest_type_id', 'ems_id'];
    public $timestamps = true;

    const WRITING_TASK_2 = 2;
    const WRITING_TASK_1 = 1;
}
