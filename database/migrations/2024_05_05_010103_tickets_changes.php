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
        Schema::table('balances', function (Blueprint $table) {
            $table->integer('sick_days')->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employee_profiles');

            $table->integer('line');
            $table->integer('urgency')->default(0);
            $table->string('resolution')->nullable();
        });

        Schema::table('employee_tickets', function (Blueprint $table) {
            $table->date('assigned_date')->nullable();
        });

        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->integer('line_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balances', function (Blueprint $table) {
            $table->dropColumn('sick_days');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('employee_id')->nullable();

            $table->dropColumn('line');
            $table->dropColumn('urgency');
            $table->dropColumn('resolution');
        });

        Schema::table('employee_tickets', function (Blueprint $table) {
            $table->dropColumn('assigned_date');
        });

        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropColumn('line_number');
        });
    }
};
