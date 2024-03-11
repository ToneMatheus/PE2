<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//customer_contracts
return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('type', 50)->nullable();
            $table->float('price', 10, 2)->nullable();
            $table->string('status', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_contracts');
    }
};
