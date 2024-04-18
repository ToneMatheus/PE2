<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//product_tariffs
return new class extends Migration
{
    public function up()
    {
        Schema::create('product_tariffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('tariff_id')->unsigned();
        });

        Schema::table('product_tariffs', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('tariff_id')->references('id')->on('tariffs');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_tariffs');
    }
};