<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes HR: balance change
return new class extends Migration
{
    public function up()
    {
        Schema::table('balance', function (Blueprint $table) {
            $table->tinyInteger('previousHolidayCredit')->default(0);
        });
    }

    public function down()
    {
        Schema::table('balance', function (Blueprint $table) {
            $table->dropColumn('previousHolidayCredit');
        });
    }
};
