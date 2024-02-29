<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//customer
return new class extends Migration
{
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('lastName', 50);
            $table->string('firstName', 50);
            $table->string('phoneNumber', 15)->nullable();
            $table->string('companyName', 50)->default('N/A');
            $table->unsignedTinyInteger('isCompany')->default(0);
            $table->bigInteger('userID')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer');
    }
};
