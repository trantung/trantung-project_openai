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
        Schema::create('user_model_data', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('model_name');
            $table->string('model_code');
            $table->string('model_ai_id');
            $table->integer('status')->nullable()->default(1);
            $table->integer('base_on_id')->nullable();
            $table->tinyInteger('type')->nullable()->default(0);
            $table->tinyInteger('approved')->nullable()->default(1);
            $table->text('note')->nullable();
            $table->string('topic')->nullable();
            $table->string('prompt')->nullable();
            $table->text('config_characters')->nullable();
            $table->text('topic_detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_model_data');
    }
};
