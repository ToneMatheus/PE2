<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//holidayType
return new class extends Migration
{
    public function up()
    {
        Schema::create('holidayType', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('type', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidayType');
    }
};
