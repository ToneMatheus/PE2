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
            $table->date('payment_date');
            $table->string('IBAN');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('structured_communication');
            $table->timestamps();
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
