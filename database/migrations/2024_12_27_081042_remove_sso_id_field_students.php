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
        //     if (Schema::hasColumn('students', 'sso_id')) {
        //         $table->dropColumn('sso_id');
        //     }
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
        //     if (!Schema::hasColumn('students', 'sso_id')) {
        //         $table->string('sso_id')->nullable();
        //     }
        // });
    }
};
