<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//consumptions
return new class extends Migration
{
    public function up()
    {
        Schema::create('consumptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->bigInteger('consumption_value');
            $table->bigInteger('prev_index_id')->unsigned()->nullable();
            $table->bigInteger('current_index_id')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('consumptions');
    }
};
