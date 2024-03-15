<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//payslips
return new class extends Migration
{
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_profile_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('creation_date');
            $table->smallInteger('nbr_days_worked')->unsigned();
            $table->float('total_hours', 10, 2)->nullable();
            $table->string('IBAN', 35);
            $table->float('amount_per_hour', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payslips');
    }
};
