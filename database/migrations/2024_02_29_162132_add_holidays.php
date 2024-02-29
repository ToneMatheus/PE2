<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//holidays
return new class extends Migration
{
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('employeeID')->unsigned();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->bigInteger('holidayTypeID')->unsigned();
            $table->string('reason', 100)->nullable();
            $table->string('fileLocation', 50)->nullable();
            $table->unsignedTinyInteger('managerApproval')->nullable();
            $table->unsignedTinyInteger('bossApproval')->nullable();
            $table->unsignedTinyInteger('active')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }
};
