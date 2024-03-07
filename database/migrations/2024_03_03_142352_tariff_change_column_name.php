<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes invoice: tariff change column name
return new class extends Migration
{
    public function up()
    {
        Schema::table('tariff', function (Blueprint $table) {
            $table->dropColumn('consumptionID');
            $table->bigInteger('customerContractID')->unsigned();
        });
    }

    public function down()
    {

    }
};
