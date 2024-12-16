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
        Schema::create('user_file_training', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_model_data_id')->nullable();
            $table->string('username')->nullable();
            $table->string('mode_ai_id_base_on')->nullable();
            $table->string('file_id')->nullable();
            $table->string('open_ai_file_id')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_file_training');
    }
};
