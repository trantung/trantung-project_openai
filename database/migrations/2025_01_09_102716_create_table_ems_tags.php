<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_ems_tags', function (Blueprint $table) {
            $table->id();
            $table->string('tag_name');
            $table->tinyInteger('tag_id')->default(1);
            $table->bigInteger('api_ems_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_ems_tags');
    }
};
