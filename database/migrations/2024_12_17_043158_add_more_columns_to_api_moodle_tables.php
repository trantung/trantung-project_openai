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
        Schema::table('api_moodle', function (Blueprint $table) {
            $table->bigInteger('quiz_submitearly')->nullable();
            $table->bigInteger('quiz_submitbuttontime')->nullable();
            $table->bigInteger('quiz_allquestions')->nullable();
            $table->bigInteger('quiz_requiredquestions')->nullable();
            $table->bigInteger('quiz_requiredquestionsPass')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_moodle', function (Blueprint $table) {
            $table->dropColumn(['quiz_submitearly', 'quiz_submitbuttontime', 'quiz_allquestions', 'quiz_requiredquestions', 'quiz_requiredquestionsPass']);
        });
    }
};
