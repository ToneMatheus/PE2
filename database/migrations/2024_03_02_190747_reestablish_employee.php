<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//reestablish employee
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
            $table->string('department', 50)->nullable();
            $table->string('phoneNumber', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('job', 50)->nullable();
            $table->string('sex', 20)->nullable();
            $table->bigInteger('addressID')->unsigned();
            $table->string('notes', 100)->nullable();
            $table->float('salaryPerMonth', 10, 2);
            $table->bigInteger('userID')->unsigned();
            $table->unsignedTinyInteger('isCustomer')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee');
    }
};
