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
        Schema::create('user_model_data_status', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->integer('user_file_training_id')->nullable();
            $table->integer('user_model_data_id')->nullable();
            $table->string('mode_ai_id_base_on')->nullable();
            $table->string('openai_job_id')->nullable();
            $table->tinyInteger('status')->nullable()->default(0);
            $table->tinyInteger('cron')->nullable()->default(0);
            $table->bigInteger('token_training')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_model_data_status');
    }
};
