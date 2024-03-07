<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//tariff drop column name
return new class extends Migration
{
    public function up()
    {
        Schema::table('tariff', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down()
    {

    }
};
