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
        //
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('description');
            $table->decimal('amount', 10, 2); // Adjust precision and scale as needed
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('credit_notes');
    }
};
