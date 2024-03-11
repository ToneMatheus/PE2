<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//estimations
return new class extends Migration
{
    public function up()
    {
        Schema::create('estimations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('nbr_occupants')->unsigned();
            $table->unsignedTinyInteger('is_home_all_day')->default(0);
            $table->unsignedTinyInteger('heat_with_power')->default(0);
            $table->unsignedTinyInteger('water_with_power')->default(0);
            $table->unsignedTinyInteger('cook_with_power')->default(0);
            $table->smallInteger('nbr_air_con')->unsigned();
            $table->smallInteger('nbr_fridges')->unsigned();
            $table->smallInteger('nbr_washers')->unsigned();
            $table->smallInteger('nbr_computers')->unsigned();
            $table->smallInteger('nbr_entertainment')->unsigned();
            $table->smallInteger('nbr_dishwashers')->unsigned();
            $table->smallInteger('estimation_total')->unsigned();
            $table->bigInteger('meter_id')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estimations');
    }
};
