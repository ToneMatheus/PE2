<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//tariffs
return new class extends Migration
{
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 50)->nullable();
            $table->bigInteger('range_min')->nullable();
            $table->bigInteger('range_max')->nullable();
            $table->float('rate', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
};
