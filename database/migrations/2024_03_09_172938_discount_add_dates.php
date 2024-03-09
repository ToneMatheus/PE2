<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//add dates to discounts
return new class extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->date('start_date');
            $table->date('end_date')->nullable();
        });
    }

    public function down()
    {

    }
};
