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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 50);
            $table->string('profile', 100)->nullable();
            $table->bigInteger('job_id')->unsigned();
            $table->tinyInteger('is_hired')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('job_offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};
