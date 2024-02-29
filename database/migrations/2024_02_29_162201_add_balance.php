<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//balance
return new class extends Migration
{
    public function up()
    {
        Schema::create('balance', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('employeeID')->unsigned();
            $table->bigInteger('holidayTypeID')->unsigned();
            $table->tinyInteger('yearlyHolidayCredit')->default(20);
            $table->unsignedTinyInteger('usedHolidayCredit')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('balance');
    }
};
