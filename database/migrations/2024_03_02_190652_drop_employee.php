<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes HR team
//drop employee
return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employee');
    }

    public function down()
    {

    }
};
