<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//holidays
return new class extends Migration
{
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_profile_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('holiday_type_id')->unsigned();
            $table->string('reason', 100)->nullable();
            $table->string('fileLocation', 50)->nullable();
            $table->unsignedTinyInteger('manager_approval')->nullable();
            $table->unsignedTinyInteger('boss_approval')->nullable();
            $table->unsignedTinyInteger('is_active')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }
};
