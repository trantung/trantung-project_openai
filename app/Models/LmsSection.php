<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsSection extends Model
{
    use HasFactory;
    
    protected $table = "lms_section";
    public $fillable = ['course_id', 'section_id'];
    public $timestamps = true;

}
