<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiMoodleEms extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'api_moodle_ems';

    protected $fillable = ['api_moodle_id', 'api_system_id', 'api_system_name'];
}
