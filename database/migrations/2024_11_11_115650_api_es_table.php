<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ĐÃ CÀI LẠI BẢNG VÀ ĐẶT TÊN LÀ API_EMS
     *
     *
     */
    public function up(): void
    {
        Schema::create('api_es', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('es_id')->nullable();
            $table->string('es_name')->nullable();
            $table->string('es_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_es');
    }
};
