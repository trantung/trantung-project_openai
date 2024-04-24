<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFileTrainings extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "user_file_training";
    public $fillable = ['user_model_data_id', 'username', 'mode_ai_id_base_on', 'file_id', 'open_ai_file_id', 'content'];
    public $timestamps = true;
}
