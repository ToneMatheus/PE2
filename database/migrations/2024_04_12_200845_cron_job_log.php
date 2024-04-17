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
        Schema::create('cron_job_run_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cron_job_run_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('log_level');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('cron_job_run_logs', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_job_run_logs');
    }
};
