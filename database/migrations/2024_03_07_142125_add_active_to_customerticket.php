<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes customerTicket: add column active
return new class extends Migration
{
    public function up()
    {
        Schema::table('customerTicket', function (Blueprint $table) {
            $table->tinyInteger('active')->default(1);
        });
    }

    public function down()
    {
        Schema::table('customerTicket', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
