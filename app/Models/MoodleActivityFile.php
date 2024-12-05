<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodleActivityFile extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'moodle_activity_file';

    protected $fillable = ['activity_id', 'moodle_id', 'moodle_type', 'file_name', 'file_path', 'file_type', 'file_size', 'uploaded_by'];
}
