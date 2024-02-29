<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//employee
return new class extends Migration
{
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('lastName', 50);
            $table->string('firstName', 50);
            $table->date('birthDate')->nullable();
            $table->date('hireDate')->nullable();
            $table->string('phoneNumber', 15)->nullable();
            $table->float('salaryPerMonth', 10, 2);
            $table->string('job', 50)->nullable();
            $table->bigInteger('addressID')->unsigned();
            $table->bigInteger('userID')->unsigned();
            $table->string('notes', 100)->nullable();
            $table->unsignedTinyInteger('isCustomer')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee');
    }
};
