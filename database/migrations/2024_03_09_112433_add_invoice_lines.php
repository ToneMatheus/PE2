<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//invoice_lines
return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 50)->nullable();
            $table->float('unit_price', 10, 2)->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->bigInteger('consumption_id')->unsigned()->nullable();
            $table->bigInteger('invoice_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_lines');
    }
};
