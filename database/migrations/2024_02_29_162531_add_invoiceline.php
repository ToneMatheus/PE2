<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//invoiceLine
return new class extends Migration
{
    public function up()
    {
        Schema::create('invoiceLine', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('type', 50)->nullable();
            $table->bigInteger('quantity')->unsigned();
            $table->float('unitPrice', 10, 2);
            $table->float('amount', 10, 2);
            $table->bigInteger('consumptionID')->unsigned()->nullable();
            $table->bigInteger('invoiceID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoiceLine');
    }
};
