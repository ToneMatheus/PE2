<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//productTariff
return new class extends Migration
{
    public function up()
    {
        Schema::create('productTariff', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->bigInteger('productID')->unsigned();
            $table->bigInteger('tariffID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productTariff');
    }
};
