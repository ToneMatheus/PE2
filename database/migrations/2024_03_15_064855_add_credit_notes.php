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
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->double('amount')->nullable(); 
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedTinyInteger('is_credit')->default(1);
            $table->unsignedTinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('credit_notes');
    }
};
