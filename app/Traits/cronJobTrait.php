<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;
use Illuminate\Support\Facades\Log;
use App\Jobs\_SendMailJob;

trait cronJobTrait
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
    
        try {
            CronJobRunLog::create([
                'cron_job_run_id' => $this->JobRunId,
                'invoice_id' => $invoiceId,
                'log_level' => $logLevelString,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error("Error occurred while logging: " . $e->getMessage());
        }
    }

    private function jobStart(){
        // Log that the job execution has started
        try {
            Log::info('Job execution started.');
    
            // Create new JobRun in the database
            $jobRun = CronJobRun::create([
                'name' => class_basename($this),
                'started_at' => Carbon::now(), 
                'ended_at' => null, 
                'status' => 'Running', 
            ]);
    
            $this->JobRunId = $jobRun->id;
        } catch (\Exception $e) {
            Log::error("Error occurred while starting the job: " . $e->getMessage());
        }
    }

    private function jobCompletion($message){
        // Log that the job execution has completed
        try {
            Log::info('Job execution completed.');
    
            $job = CronJobRun::find($this->JobRunId);
            $job->ended_at = now();
    
            if (empty($job->error_message)) {
                $job->status = 'Completed';
                $job->error_message = $message;
            } else {
                $job->status = 'Failed';
            }
    
            $job->save();
        } catch (\Exception $e) {
            Log::error("Error occurred while completing the job: " . $e->getMessage());
        }
    }

    private function jobException($errorMessage){
        // Log the crash that happened
        try {
            Log::info('Job had an exception');
    
            $job = CronJobRun::find($this->JobRunId);
            $job->ended_at = now();
            $job->status = 'Failed';
            $job->error_message = $errorMessage;
    
            $job->save();
        } catch (\Exception $e) {
            Log::error("Error occurred while handling logging job exception: " . $e->getMessage());
        }
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

    public function sendMailInBackground($mailTo, $mailableClass, $mailableClassParams){
        if (env('APP_DEBUG')) {
            // Debug mode is enabled so use debugging mail instead of provided mail
            $mailTo = env("MAIL_DEBUG");
        }
        _SendMailJob::dispatch($mailTo, $mailableClass, $mailableClassParams , $this->JobRunId);
    }

}