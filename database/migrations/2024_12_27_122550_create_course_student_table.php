<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->unsigned()->index(); // Khóa ngoại đến bảng khóa học
            $table->bigInteger('student_id')->unsigned()->index(); // Khóa ngoại đến bảng học sinh
            $table->timestamps(); // Trường created_at và updated_at
            $table->softDeletes(); // Trường deleted_at để hỗ trợ xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
