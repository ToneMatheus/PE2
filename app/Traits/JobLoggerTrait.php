<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;
use Illuminate\Support\Facades\Log;

trait jobLoggerTrait
{
    private $JobRunId;

    private function __Log($logLevel, $invoiceId, $message) {
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

    private function jobStart(){
        // Log that the job execution has started
        Log::info('Job execution started.');

        // Create new JobRun in the database
        $jobRun = CronJobRun::create([
            'name' => class_basename($this),
            'started_at' => Carbon::now(), 
            'ended_at' => null, 
            'status' => 'Started', 
        ]);

        $this->JobRunId = $jobRun->id;
    }

    private function jobCompletion($message){
        // Log that the job execution has completed
        Log::info('Job execution completed.');

        $job = CronJobRun::find($this->JobRunId);
        $job->ended_at = now();

        if (empty($job->error_message)) {
            $job->status = 'completed';
        } else {
            $job->status = 'failed';
        }

        $job->save();
    }

    private function jobException($errorMessage){
        // Log the crash that happened
        Log::info('Job had an exception');

        $job = CronJobRun::find($this->JobRunId);
        $job->ended_at = now();
        $job->status = 'failed';
        $job->error_message = $errorMessage;

        $job->save();
    }

    public function logInfo($invoiceId, $message){
        $this->__Log(1, $invoiceId, $message);
    }

    public function logWarning($invoiceId, $message){
        $this->__Log(2, $invoiceId, $message);
    }

    public function logCritical($invoiceId, $message){
        $this->__Log(3, $invoiceId, $message);
    }

    public function logError($invoiceId, $message){
        $this->__Log(4, $invoiceId, $message);
    }

}