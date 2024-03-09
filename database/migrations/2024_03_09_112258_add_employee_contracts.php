<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//employee_contracts
return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_profile_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('status', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_contracts');
    }
};
