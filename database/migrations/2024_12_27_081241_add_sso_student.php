<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Schema::table('students', function (Blueprint $table) {
        //     $table->string('sso_name')->nullable();
        //     $table->string('sso_id')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('students', function (Blueprint $table) {
        //     $table->dropColumn('sso_name');
        //     $table->dropColumn('sso_id');
        // });
    }
};
