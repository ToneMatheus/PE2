<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//addresses
return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('street', 50);
            $table->unsignedSmallInteger('number');
            $table->string('box', 4);
            $table->unsignedSmallInteger('postal_code');
            $table->string('city', 50);
            $table->string('province', 50);
            $table->string('country', 50)->default('Belgium');
            $table->enum('type', ['house', 'appartment', 'business'])->nullable();
            $table->unsignedTinyInteger('is_billing_address')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
