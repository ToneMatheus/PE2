<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CronJobHistory;
use App\Models\CronJob;
use Carbon\Carbon;

class TemplateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Log that the job execution has started
            $this->logStart();

            /**
             * job logic goes here
             *
             *
             */

            // Log that the job execution has completed
            $this->logComplete();
        } catch (\Exception $e) {
            // Log any errors that occur during job execution
            $this->logError($e->getMessage());
        }
    }

    private function logStart()
    {
        // Log that the job execution has started
        \Log::info('Job execution started.');

        // You can also log to the database if needed
        CronJobHistory::create([
            'job_name' => class_basename($this),
            'status' => 'Started',
            'completed_at' => Carbon::now(),
        ]);
    }

    private function logComplete()
    {
        // Log that the job execution has completed
        \Log::info('Job execution completed.');

        // You can also log to the database if needed
        CronJobHistory::create([
            'job_name' => class_basename($this),
            'status' => 'Completed',
            'completed_at' => Carbon::now(),
        ]);
    }

    private function logError($errorMessage)
    {
        // Log any errors that occur during job execution
        \Log::error('Job execution failed: ' . $errorMessage);

        // You can also log to the database if needed
        CronJobHistory::create([
            'job_name' => class_basename($this),
            'status' => 'Error: ' . $errorMessage,
            'completed_at' => Carbon::now(),
        ]);
    }
}
