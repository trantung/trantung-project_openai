<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use HasFactory, SoftDeletes;
    public $paginate = 10;
    protected $table = 'user_role';

    protected $fillable = ['role_id', 'user_id'];
}
