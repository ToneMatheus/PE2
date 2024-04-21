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
        Schema::create('credit_note_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product');
            $table->integer('quantity');
            $table->float('price')->default(0.0); 
            $table->float('amount')->default(0.0);
            $table->foreignId('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('credit_note_lines');
    }
};
