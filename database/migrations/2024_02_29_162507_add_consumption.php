<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//consumption
return new class extends Migration
{
    public function up()
    {
        Schema::create('consumption', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->bigInteger('value');
            $table->bigInteger('previousIndexID')->unsigned()->nullable();
            $table->bigInteger('currentIndexID')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('consumption');
    }
};
