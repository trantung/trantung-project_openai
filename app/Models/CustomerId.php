<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerId extends Model
{
    use HasFactory;
    protected $table = "customer_id";
    public $fillable = ['topic', 'question', 'answer', 'user_id', 'status'];
    public $timestamps = true;
}
