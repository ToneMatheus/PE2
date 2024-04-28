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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_landlord')->nullable(); //0: not a landlord of company, 1: landlord of company building, null: not a company
        });

        Schema::table('meter_reader_schedules', function (Blueprint $table) {
            $table->unsignedTinyInteger('priority')->default(0); //1: has priority, 0: no priority
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->timestamps();
            $table->string('structured_communication', 21)->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->date('close_date')->nullable();
            $table->unsignedTinyInteger('is_solved')->default(0);
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_applied')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_landlord');
        });

        Schema::table('meter_reader_schedules', function (Blueprint $table) {
            $table->dropColumn('priority');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('structured_communication');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('close_date');
            $table->dropColumn('is_solved');
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->dropColumn('is_applied');
        });
    }
};
