<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//contractProduct
return new class extends Migration
{
    public function up()
    {
        Schema::create('contractProduct', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('customerContractID')->unsigned()->nullable();
            $table->bigInteger('tariffID')->unsigned()->nullable();
            $table->bigInteger('productID')->unsigned()->nullable();
            $table->date('startDate');
            $table->date('endDate')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contractProduct');
    }
};
