<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes invoice: tariff delete start/end date + FK, product delete FK
return new class extends Migration
{
    public function up()
    {
        Schema::table('tariff', function (Blueprint $table) {
            $table->dropColumn('customerContractID');
            $table->dropColumn('startDate');
            $table->dropColumn('endDate');
        });

        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('customerContractID');
        });
    }

    public function down()
    {

    }
};
