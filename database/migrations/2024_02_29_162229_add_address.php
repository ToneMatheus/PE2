<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//address
return new class extends Migration
{
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('street', 50);
            $table->unsignedSmallInteger('number');
            $table->unsignedSmallInteger('postalCode');
            $table->string('bus', 4);
            $table->string('city', 50);
            $table->string('region', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('address');
    }
};
