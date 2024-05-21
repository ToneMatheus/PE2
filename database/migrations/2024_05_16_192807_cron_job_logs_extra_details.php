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
        Schema::table('cron_job_run_logs', function (Blueprint $table) {
            $table->longText('detailed_message')->nullable();
            $table->text('job_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cron_job_run_logs', function (Blueprint $table) {
            $table->dropColumn('detailed_message');
            $table->dropColumn('job_name');
        });
    }
};
