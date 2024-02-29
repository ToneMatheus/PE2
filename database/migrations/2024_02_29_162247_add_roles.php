<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//roles
return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('roleDescription', 100);
            $table->bigInteger('userID')->unsigned();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
