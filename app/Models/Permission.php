<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'permission';

    protected $fillable = ['name', 'route_name', 'action', 'method', 'description'];

    // Mối quan hệ: Một quyền có thể có nhiều vai trò
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'role_permission', 'permission_id', 'role_id');
    }
}
