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
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('interval')->nullable();
            $table->integer('scheduled_day')->nullable();
            $table->integer('scheduled_month')->nullable();
            $table->time('scheduled_time');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('cron_job_histories', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->string('status');
            $table->timestamp('completed_at');
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
        Schema::dropIfExists('cron_job_histories');
        Schema::dropIfExists('cron_jobs');
    }
};