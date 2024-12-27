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
        Schema::create('rubric_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rubric_template_id');
            $table->integer('lms_score')->nullable();
            $table->integer('rule_score')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rubric_template_id')->references('id')->on('rubric_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubric_score');
    }
};
