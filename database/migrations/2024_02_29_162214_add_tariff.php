<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//tariff
return new class extends Migration
{
    public function up()
    {
        Schema::create('tariff', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('name', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->bigInteger('rangeMin')->nullable();
            $table->bigInteger('rangeMax')->nullable();
            $table->float('rate', 10, 2)->nullable();
            $table->bigInteger('consumptionID')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tariff');
    }
};
