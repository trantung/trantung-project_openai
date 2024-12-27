<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use HasFactory, SoftDeletes;
    public $paginate = 10;
    protected $table = 'students';

    protected $fillable = ['user_id', 'name', 'username', 'email', 'sso_id', 'sso_name'];
}
