<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//employeeContract
return new class extends Migration
{
    public function up()
    {
        Schema::create('employeeContract', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('employeeID')->unsigned();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('status', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employeeContract');
    }
};
