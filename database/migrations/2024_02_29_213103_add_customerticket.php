<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//customerTicket
return new class extends Migration
{
    public function up()
    {
        Schema::create('customerTicket', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('name', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('issue', 50)->nullable();
            $table->string('description', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customerTicket');
    }
};
