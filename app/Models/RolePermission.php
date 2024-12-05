<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'role_permission';

    protected $fillable = ['role_id', 'permission_id'];
}
