<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//contract_products
return new class extends Migration
{
    public function up()
    {
        Schema::create('contract_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_contract_id')->unsigned()->nullable();
            $table->bigInteger('tariff_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contract_products');
    }
};
