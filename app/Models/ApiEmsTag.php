<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEmsTag extends Model
{
    use HasFactory;
    protected $table = 'api_ems_tags';
    protected $fillable = ['tag_name', 'tag_id', 'api_ems_id'];
    
}
