<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiMoodle extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'api_moodle';

    protected $fillable = ['moodle_id', 'moodle_name', 'moodle_type', 'parent_id', 'creator', 'modifier', 'level'];
}
