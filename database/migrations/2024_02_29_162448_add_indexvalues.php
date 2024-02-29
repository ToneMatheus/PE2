<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//indexValues
return new class extends Migration
{
    public function up()
    {
        Schema::create('indexValues', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('readingDate');
            $table->bigInteger('readingValue')->unsigned();
            $table->bigInteger('meterID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indexValues');
    }
};
