<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//meter_addresses
return new class extends Migration
{
    public function up()
    {
        Schema::create('meter_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('address_id')->unsigned();
            $table->bigInteger('meter_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meter_addresses');
    }
};
