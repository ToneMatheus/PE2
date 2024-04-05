<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('job_title')->unsigned();
            $table->string('description', 1000);
            $table->string('image', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_offers');
    }
};
