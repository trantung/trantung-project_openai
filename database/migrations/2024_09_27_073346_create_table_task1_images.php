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
        Schema::create('task1_images', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('user_id')->nullable();
            $table->text('question')->nullable();
            $table->text('topic')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task1_images');
    }
};
