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
        if (Schema::hasTable('api_user_questions') && !Schema::hasColumn('ems_id', 'contest_type_id')) {
            Schema::table('api_user_questions', function (Blueprint $table) {
                $table->integer('contest_type_id')->default(0);
                $table->bigInteger('ems_id')->default(0);
            });
        }
        if (Schema::hasTable('api_user_question_parts') && !Schema::hasColumn('ems_id', 'contest_type_id')) {
            Schema::table('api_user_question_parts', function (Blueprint $table) {
                $table->integer('contest_type_id')->default(0);
                $table->bigInteger('ems_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('api_user_questions') && Schema::hasColumn('ems_id', 'contest_type_id')) {
            Schema::table('api_user_questions', function (Blueprint $table) {
                $table->dropColumn('contest_type_id');
                $table->dropColumn('ems_id');
            });
        }
        if (Schema::hasTable('api_user_question_parts') && !Schema::hasColumn('ems_id', 'contest_type_id')) {
            Schema::table('api_user_question_parts', function (Blueprint $table) {
                $table->integer('contest_type_id')->default(0);
                $table->bigInteger('ems_id')->default(0);
            });
        }
    }
};
