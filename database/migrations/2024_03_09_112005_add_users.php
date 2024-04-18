<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//users
return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 50);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('password', 100);
            $table->bigInteger('employee_profile_id')->unsigned()->nullable();
            $table->unsignedTinyInteger('is_company')->default(0);
            $table->string('company_name', 50)->nullable();
            $table->string('email', 50);
            $table->string('phone_nbr', 15);
            $table->date('birth_date')->nullable();
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->enum('title', ['Mr', 'Ms', 'X'])->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
