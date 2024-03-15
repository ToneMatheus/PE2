<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//meter_reader_schedules
return new class extends Migration
{
    public function up()
    {
        Schema::create('meter_reader_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_profile_id')->unsigned();
            $table->date('reading_date')->nullable();
            $table->bigInteger('meter_id')->unsigned();
            $table->string('status', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meter_reader_schedules');
    }
};
