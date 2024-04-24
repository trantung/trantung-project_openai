<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class UserModelData extends Model
{
    use HasFactory;

    const OPENAI_TRAINING = 0;
    const OPENAI_SUCCESS = 1;
    const OPENAI_ERROR = 2;

    protected $table = "user_model_data";
    public $fillable = ['username', 'model_name', 'model_code', 'model_ai_id', 'status', 'base_on_id', 'type', 'approved', 'note', 'topic', 'prompt', 'config_characters', 'topic_detail'];
    public $timestamps = true;

}
