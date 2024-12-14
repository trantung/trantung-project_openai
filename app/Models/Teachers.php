<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teachers extends Model
{
    use HasFactory, SoftDeletes;
    public $paginate = 10;
    protected $table = 'teachers';

    protected $fillable = ['user_id', 'name', 'username', 'email', 'sso_id', 'sso_name'];

    public function courses()
    {
        return $this->belongsToMany(ApiMoodle::class, 'course_teacher', 'teacher_id', 'course_id');
    }

    public function roles()
    {
        return $this->hasOneThrough(
            Roles::class,
            UserRole::class,
            'user_id',  // Foreign key on user_role table
            'id',       // Foreign key on roles table
            'user_id',  // Local key on teachers table
            'role_id'   // Local key on user_role table
        );
    }

    public function classes()
    {
        return $this->hasManyThrough(
            Classes::class,
            CourseTeacher::class,
            'teacher_id', // Khóa ngoại trên bảng course_teacher
            'id',         // Khóa ngoại trên bảng classes
            'id',         // Khóa chính trên bảng teachers
            'class_id'    // Khóa chính trên bảng course_teacher
        )->whereNull('course_teacher.deleted_at'); // Chỉ lấy các bản ghi không bị xóa mềm
    }

    public function scopeByName($query, $name)
    {
        return $query->where('name', 'LIKE', "%{$name}%");
    }

    // Scope để tìm giáo viên theo email
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', 'LIKE', "%{$email}%");
    }

    public function scopeByUsername($query, $username)
    {
        return $query->where('username', 'LIKE', "%{$username}%");
    }

    public static function searchTeachers($searchTerm)
    {
        return self::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->byName($searchTerm)
                    ->orWhere(function ($query) use ($searchTerm) {
                        $query->byEmail($searchTerm)
                            ->orWhere('username', 'LIKE', "%{$searchTerm}%"); // Sử dụng orWhere cho username
                    });
            });
    }
}
