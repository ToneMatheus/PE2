<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//addressMeter
return new class extends Migration
{
    public function up()
    {
        Schema::create('addressMeter', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->bigInteger('addressID')->unsigned();
            $table->bigInteger('meterID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addressMeter');
    }
};
