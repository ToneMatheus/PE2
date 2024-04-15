<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->string('work_email', 50);
            $table->dropColumn('department');
            $table->dropColumn('sex');
            $table->dropColumn('nationality');
            $table->string('password', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropColumn('work_email');
            $table->string('department', 50)->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->dropColumn('password');
        });
    }
};
