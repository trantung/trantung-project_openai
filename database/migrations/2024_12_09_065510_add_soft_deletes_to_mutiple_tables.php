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
        // Thêm softDeletes vào bảng roles
        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Thêm softDeletes vào bảng user_role
        Schema::table('user_role', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Thêm softDeletes vào bảng role_permission
        Schema::table('role_permission', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Thêm softDeletes vào bảng permission
        // Schema::table('permission', function (Blueprint $table) {
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa cột softDeletes khỏi bảng roles
        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Xóa cột softDeletes khỏi bảng user_role
        Schema::table('user_role', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Xóa cột softDeletes khỏi bảng role_permission
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Xóa cột softDeletes khỏi bảng permission
        // Schema::table('permission', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
    }
};
