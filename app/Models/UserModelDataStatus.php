<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModelDataStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    const RUN = 1;
    const NOT_RUN = 0;
    const STATUS_VALIDATE = 0;
    const STATUS_TRAINING = 1;
    const STATUS_COMPLETE = 2;

    protected $table = "user_model_data_status";
    public $fillable = ['username', 'user_file_training_id', 'user_model_data_id', 'mode_ai_id_base_on', 'openai_job_id', 'status', 'cron', 'token_training'];
    public $timestamps = true;
}
