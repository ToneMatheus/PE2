<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//balances
return new class extends Migration
{
    public function up()
    {
        Schema::create('leader_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('leader_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();
            $table->enum('relation', ['manager', 'boss']);
        });

        Schema::table('leader_relations', function (Blueprint $table) {
            $table->foreign('leader_id')->references('id')->on('users');
            $table->foreign('employee_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leader_relations');
    }
};