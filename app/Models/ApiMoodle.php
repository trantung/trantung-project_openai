<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApiMoodle extends Model
{
    use HasFactory;
    public $paginate = 10;
    protected $table = 'api_moodle';

    protected $fillable = [
        'moodle_id',
        'moodle_name',
        'moodle_type',
        'parent_id',
        'creator',
        'modifier',
        'level',
        'quiz_submitearly', // Cho phép nộp bài trước thời gian
        'quiz_submitbuttontime', // Thời gian hiển thị nút submit (Phút)
        'quiz_allquestions', // Nộp bài sau khi hoàn thành (Tất cả câu hỏi)
        'quiz_requiredquestions', // Nộp bài sau khi hoàn thành (Yêu cầu số lượng câu hỏi để hoàn thành)
        'quiz_requiredquestionsPass',// Số lượng câu hỏi cần hoàn thành
        'quiz_settings_type' // Loại học liệu
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_course', 'course_id', 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teachers::class, 'course_teacher', 'course_id', 'teacher_id');
    }

    /**
     * @reutrn HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'api_moodle_id');
    }

    /**
     * @reutrn HasMany
     */
    public function rubric_template(): BelongsTo
    {
        return $this->belongsTo(RubricTemplate::class);
    }

    public function scopeMoodleType($query, $type)
    {
        return $query->where('moodle_type', $type);
    }

    public function scopeInIds($query, $ids)
    {
        return $query->whereIn('id', $ids);
    }

    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
}
