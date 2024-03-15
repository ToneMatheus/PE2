<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//customer_addresses
return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('address_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
};

