<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//employee_profiles
return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('job', 50)->nullable();
            $table->date('hire_date');
            $table->string('department', 50)->nullable();
            $table->string('notes', 100)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('sex', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_profiles');
    }
};
