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
        // Thêm cột 'code' vào bảng 'api_moodle' nếu bảng tồn tại
        if (Schema::hasTable('api_moodle') && !Schema::hasColumn('api_moodle', 'code')) {
            Schema::table('api_moodle', function (Blueprint $table) {
                $table->string('code')->nullable();
            });
        }

        // Thêm cột 'code' vào bảng 'classes' nếu bảng tồn tại
        if (Schema::hasTable('classes') && !Schema::hasColumn('classes', 'code')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->string('code')->nullable();
            });
        }

        // Thêm cột 'code' vào bảng 'courses' nếu bảng tồn tại
        if (Schema::hasTable('courses') && !Schema::hasColumn('courses', 'code')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->string('code')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa cột 'code' khỏi bảng 'api_moodle' nếu tồn tại
        if (Schema::hasTable('api_moodle') && Schema::hasColumn('api_moodle', 'code')) {
            Schema::table('api_moodle', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }

        // Xóa cột 'code' khỏi bảng 'classes' nếu tồn tại
        if (Schema::hasTable('classes') && Schema::hasColumn('classes', 'code')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }

        // Xóa cột 'code' khỏi bảng 'courses' nếu tồn tại
        if (Schema::hasTable('courses') && Schema::hasColumn('courses', 'code')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }
};
