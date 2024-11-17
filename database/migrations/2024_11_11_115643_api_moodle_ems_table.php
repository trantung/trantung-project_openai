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
        Schema::create('api_moodle_ems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('api_moodle_id')->nullable();
            $table->bigInteger('api_system_id')->nullable();
            $table->string('api_system_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_moodle_ems');
    }
};
