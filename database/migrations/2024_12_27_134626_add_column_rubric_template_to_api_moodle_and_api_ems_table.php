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
        Schema::table('api_moodle', function (Blueprint $table) {
            $table->unsignedBigInteger('rubric_template_id')->nullable();

            $table->foreign('rubric_template_id')->references('id')->on('rubric_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_moodle', function (Blueprint $table) {
            $table->dropForeign(['rubric_template_id']);
            $table->dropColumn('rubric_template_id');
        });
    }
};
