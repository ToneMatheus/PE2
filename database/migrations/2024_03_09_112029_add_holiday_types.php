<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//holiday_types
return new class extends Migration
{
    public function up()
    {
        Schema::create('holiday_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('holiday_types');
    }
};

