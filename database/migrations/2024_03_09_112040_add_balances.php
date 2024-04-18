<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//balances
return new class extends Migration
{
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_profile_id')->unsigned();
            $table->bigInteger('holiday_type_id')->unsigned();
            $table->tinyInteger('yearly_holiday_credit')->default(20);
            $table->unsignedTinyInteger('used_holiday_credit')->default(0);
            $table->tinyInteger('previous_holiday_credit')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('balances');
    }
};
