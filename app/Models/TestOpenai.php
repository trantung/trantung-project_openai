<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestOpenai extends Model
{
    use HasFactory;
    protected $table = "test_openais";
    public $fillable = ['name', 'topic', 'category_id', 'question', 'answer', 'total_token', 'prompt_token', 'complete_token'];
    public $timestamps = true;
}
