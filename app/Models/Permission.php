<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'permission';

    protected $fillable = ['name', 'route_name', 'action', 'method', 'description'];
}
