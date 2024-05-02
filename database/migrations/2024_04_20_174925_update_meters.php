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
        Schema::table('meters', function (Blueprint $table) {
            $table->unsignedTinyInteger('expecting_reading')->default(0);
            $table->unsignedTinyInteger('has_validation_error')->default(0); //can we currently send an invoice for this meter? 0: no error -> send invoice, 1: error -> no invoice
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->dropColumn('expecting_reading');
            $table->dropColumn('has_validation_error');
        });
    }
};
