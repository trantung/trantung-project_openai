<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEs extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'api_es';

    protected $fillable = ['es_id', 'es_name', 'es_type'];
}
