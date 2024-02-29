<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//invoice
return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('invoiceDate');
            $table->date('dueDate');
            $table->float('totalAmount', 10, 2);
            $table->string('status', 50)->default('draft');
            $table->bigInteger('customerContractID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice');
    }
};
