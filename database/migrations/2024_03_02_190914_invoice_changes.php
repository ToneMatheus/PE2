<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//changes invoice
return new class extends Migration
{
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->string('type', 50)->nullable();
            $table->dropColumn('unitPrice');
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->string('type', 50);
        });

        Schema::table('invoiceLine', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }

    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->float('unitPrice', 10, 2);
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('invoiceLine', function (Blueprint $table) {
            $table->bigInteger('quantity')->unsigned();
        });
    }
};
