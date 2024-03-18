<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//change salary
return new class extends Migration
{
    public function up()
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropcolumn('salary_per_month');
        });

        Schema::table('employee_contracts', function (Blueprint $table) {
            $table->float('salary_per_month', 10, 2);
        });
    }

    public function down()
    {

    }
};

