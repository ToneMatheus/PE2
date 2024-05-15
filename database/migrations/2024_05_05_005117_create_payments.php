<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('amount', 10, 2);
            $table->date('payment_date');
            $table->string('IBAN');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('structured_communication');
            $table->unsignedTinyInteger('has_matched')->default(0);
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('IBAN')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('IBAN');
        });
    }
};
