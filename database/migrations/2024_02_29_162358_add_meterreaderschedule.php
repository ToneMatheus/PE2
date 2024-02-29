<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//meterReaderSchedule
return new class extends Migration
{
    public function up()
    {
        Schema::create('meterReaderSchedules', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('readingDate');
            $table->string('status', 50)->nullable();
            $table->bigInteger('employeeID')->unsigned();
            $table->bigInteger('meterID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meterReaderSchedule');
    }
};
