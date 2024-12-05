<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'user_role';

    protected $fillable = ['role_id', 'user_id'];
}
