<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//invoices
return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->float('total_amount', 10, 2);
            $table->enum('status', ['pending', 'sent', 'paid', 'validation ok', 'validation error 1', 'validation error 2', 'validation error 3'])->default('pending');
            $table->bigInteger('customer_contract_id')->unsigned();
            $table->string('type', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
