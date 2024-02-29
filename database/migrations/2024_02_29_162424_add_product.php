<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//product
return new class extends Migration
{
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('productName', 50);
            $table->string('description', 100)->nullable();
            $table->float('unitPrice', 10, 2);
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->bigInteger('customerContractID')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
};
