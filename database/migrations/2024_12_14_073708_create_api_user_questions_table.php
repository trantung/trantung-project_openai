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
        if (!Schema::hasTable('api_user_question_parts')) {
            Schema::create('api_user_questions', function (Blueprint $table) {
                $table->id();
                $table->string('user_id')->nullable();
                $table->string('username')->nullable();
                $table->text('topic')->nullable();
                $table->text('question')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->bigInteger('prompt_token')->nullable();
                $table->bigInteger('total_token')->nullable();
                $table->bigInteger('complete_token')->nullable();
                $table->text('openai_response')->nullable();
                $table->tinyInteger('writing_task_number')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_user_questions');
    }
};
