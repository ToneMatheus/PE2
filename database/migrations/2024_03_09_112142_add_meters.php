<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//meters
return new class extends Migration
{
    public function up()
    {
        Schema::create('meters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('EAN', 20)->nullable();
            $table->string('type', 50)->nullable();
            $table->date('installation_date')->nullable();
            $table->string('status', 50)->nullable();
            $table->smallInteger('is_smart')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meters');
    }
};
