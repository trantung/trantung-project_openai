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
        Schema::create('test_openais', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('topic')->nullable();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('total_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_openais');
    }
};
