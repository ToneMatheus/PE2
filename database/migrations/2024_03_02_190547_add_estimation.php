<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//estimation
return new class extends Migration
{
    public function up()
    {
        Schema::create('estimation', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->smallInteger('nrOccupants')->unsigned();
            $table->unsignedTinyInteger('isHomeAllDay')->default(0);
            $table->unsignedTinyInteger('heatWithPower')->default(0);
            $table->unsignedTinyInteger('waterWithPower')->default(0);
            $table->unsignedTinyInteger('cookWithPower')->default(0);
            $table->smallInteger('nrAirCon')->unsigned();
            $table->smallInteger('nrFridges')->unsigned();
            $table->smallInteger('nrWashers')->unsigned();
            $table->smallInteger('nrComputers')->unsigned();
            $table->smallInteger('nrEntertainment')->unsigned();
            $table->smallInteger('nrDishwashers')->unsigned();
            $table->smallInteger('estimationTotal')->unsigned();
            $table->bigInteger('meterID')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estimation');
    }
};
