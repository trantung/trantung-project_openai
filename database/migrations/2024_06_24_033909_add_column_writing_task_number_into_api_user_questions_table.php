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
        Schema::table('api_user_question_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('api_user_question_parts', 'writing_task_number')) {
                $table->integer('writing_task_number')->default(2);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
