<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//index_values
return new class extends Migration
{
    public function up()
    {
        Schema::create('index_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('reading_date');
            $table->bigInteger('reading_value')->unsigned();
            $table->bigInteger('meter_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('index_values');
    }
};
