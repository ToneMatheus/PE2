<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//user_roles
return new class extends Migration
{
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->unsignedTinyInteger('is_active')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
};
