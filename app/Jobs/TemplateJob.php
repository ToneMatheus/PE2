<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;
use Carbon\Carbon;

class TemplateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $JobRunId;

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
            $this->Start();
            $this->jobLogic();
            $this->Complete();
        } catch (\Throwable $e) {
            // Catch any throwable errors, including errors and exceptions
            $this->logException($e->getMessage());
        }
    }

    private function Start(){
        // Log that the job execution has started
        \Log::info('Job execution started.');

        // Create new JobRun in the database
        $jobRun = CronJobRun::create([
            'name' => class_basename($this),
            'started_at' => Carbon::now(), 
            'ended_at' => null, 
            'status' => 'Started', 
        ]);

        $this->JobRunId = $jobRun->id;
    }

    private function Complete(){
        // Log that the job execution has completed
        \Log::info('Job execution completed.');

        $job = CronJobRun::find($this->JobRunId);
        $job->ended_at = now();

        if (empty($job->error_message)) {
            $job->status = 'completed';
        } else {
            $job->status = 'failed';
        }

        $job->save();
    }

    private function Log($logLevel, $invoiceId, $message) {
        $logLevelMap = [
            1 => "Info",
            2 => "Warning",
            3 => "Critical",
            4 => "Error",
        ];
    
        $logLevelString = $logLevelMap[$logLevel] ?? "Unknown";
    
        CronJobRunLog::create([
            'cron_job_run_id' => $this->JobRunId,
            'invoice_id' => $invoiceId,
            'log_level' => $logLevelString,
            'message' => $message,
        ]);
    }

    private function logException($errorMessage){
        // Log the crash that happened
        \Log::info('Job had an exception');

        $job = CronJobRun::find($this->JobRunId);
        $job->ended_at = now();
        $job->status = 'failed';
        $job->error_message = $errorMessage;

        $job->save();
    }

    private function jobLogic(){
        /*
        *   your job logic goes here
        *   
        *
        */
        $messages = [
            "this is an info message",
            "this is a warning",
            "this is a critical error",
            "oh no i did big oopsie"
        ];
        
        for ($i = 0; $i < 1000; $i++) {
            $logLevel = rand(1, 4); 
            $message = $messages[array_rand($messages)];
            $this->Log($logLevel, rand(1,5000) ,$message);
        }
    }
}
