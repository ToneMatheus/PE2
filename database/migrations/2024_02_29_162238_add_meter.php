<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//meter
return new class extends Migration
{
    public function up()
    {
        Schema::create('meter', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('EAN', 20)->nullable();
            $table->string('type', 50)->nullable();
            $table->date('installationDate')->nullable();
            $table->string('status', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meter');
    }
};
