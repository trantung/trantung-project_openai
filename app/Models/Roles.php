<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roles extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'roles';

    protected $fillable = ['name'];
}
