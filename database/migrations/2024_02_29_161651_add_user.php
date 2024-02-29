<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//user
return new class extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('username', 50);
            $table->string('password', 100);
            $table->string('email', 50)->nullable();
            $table->unsignedTinyInteger('isActivated')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
};
