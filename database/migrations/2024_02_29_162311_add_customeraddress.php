<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//customerAddress
return new class extends Migration
{
    public function up()
    {
        Schema::create('customerAddress', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->bigInteger('customerID')->unsigned();
            $table->bigInteger('addressID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customerAddress');
    }
};
