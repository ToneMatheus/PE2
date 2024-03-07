<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//payslips
return new class extends Migration
{
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->date('creationDate');
            $table->smallInteger('nrDaysWorked')->unsigned();
            $table->float('totalHours', 10, 2)->nullable();
            $table->string('IBAN', 35);
            $table->float('amountPerHour', 10, 2)->nullable();
            $table->bigInteger('employeeID')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payslips');
    }
};
