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
        Schema::create('api_moodle', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('moodle_id')->nullable();
            $table->string('moodle_name')->nullable();
            $table->string('moodle_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_moodle');
    }
};
