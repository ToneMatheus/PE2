<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;
use Illuminate\Support\Facades\Log;

trait jobLoggerTrait
{
    private function __Log($jobRunId, $logLevel, $invoiceId, $message) {
        $logLevelMap = [
            1 => "Info",
            2 => "Warning",
            3 => "Critical",
            4 => "Error",
        ];
    
        $logLevelString = $logLevelMap[$logLevel] ?? "Unknown";
    
        CronJobRunLog::create([
            'cron_job_run_id' => $jobRunId,
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

        return $jobRun->id;
    }

    private function jobCompletion($jobRunId){
        // Log that the job execution has completed
        Log::info('Job execution completed.');

        $job = CronJobRun::find($jobRunId);
        $job->ended_at = now();

        if (empty($job->error_message)) {
            $job->status = 'completed';
        } else {
            $job->status = 'failed';
        }

        $job->save();
    }

    private function jobException($jobRunId, $errorMessage){
        // Log the crash that happened
        Log::info('Job had an exception');

        $job = CronJobRun::find($jobRunId);
        $job->ended_at = now();
        $job->status = 'failed';
        $job->error_message = $errorMessage;

        $job->save();
    }

    public function logInfo($jobRunId, $invoiceId, $message){
        $this->__Log($jobRunId, 1, $invoiceId, $message);
    }

    public function logWarning($jobRunId, $invoiceId, $message){
        $this->__Log($jobRunId, 2, $invoiceId, $message);
    }

    public function logCritical($jobRunId, $invoiceId, $message){
        $this->__Log($jobRunId, 3, $invoiceId, $message);
    }

    public function logError($jobRunId, $invoiceId, $message){
        $this->__Log($jobRunId, 4, $invoiceId, $message);
    }

}