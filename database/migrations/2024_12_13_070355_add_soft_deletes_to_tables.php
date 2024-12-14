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
        // Thêm softDeletes vào bảng teachers
        Schema::table('teachers', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Thêm softDeletes vào bảng classes
        Schema::table('classes', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa cột softDeletes khỏi bảng teachers
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Xóa cột softDeletes khỏi bảng classes
        Schema::table('classes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
