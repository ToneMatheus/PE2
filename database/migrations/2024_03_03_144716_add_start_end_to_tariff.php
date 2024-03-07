<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes invoice: tariff add start/end date
return new class extends Migration
{
    public function up()
    {
        Schema::table('tariff', function (Blueprint $table) {
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tariff', function (Blueprint $table) {
            $table->dropColumn('startDate');
            $table->dropColumn('endDate');
        });
    }
};
