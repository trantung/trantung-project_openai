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
        Schema::create('api_ems', function (Blueprint $table) {
            $table->id();
            $table->integer('ems_id')->nullable();
            $table->string('ems_name')->nullable();
            $table->unsignedBigInteger('ems_type_id')->nullable();
            $table->unsignedBigInteger('rubric_template_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('rubric_template_id')->references('id')->on('rubric_templates')->onDelete('cascade');
            $table->foreign('ems_type_id')->references('id')->on('ems_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_ems');
    }
};
