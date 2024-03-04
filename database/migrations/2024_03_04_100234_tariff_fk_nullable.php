<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes invoice: tariff make customerContractID nullable
return new class extends Migration
{
    public function up()
    {
        Schema::table('tariff', function (Blueprint $table) {
            $table->dropColumn('customerContractID');
        });

        Schema::table('tariff', function (Blueprint $table) {
            $table->bigInteger('customerContractID')->unsigned()->nullable();
        });
    }

    public function down()
    {

    }
};
