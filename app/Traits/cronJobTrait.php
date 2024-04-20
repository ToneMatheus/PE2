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

    private function __getShortClassName(){
        $className = get_class($this);
        $classNameParts = explode('\\', $className);
        return end($classNameParts);
    }

    private function jobStart(){
        // Log that the job execution has started
        try {
            Log::info('Job: '.$this->__getShortClassName().' execution started.');
    
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
            Log::info('Job: '.$this->__getShortClassName().' execution completed.');
    
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
            Log::info('Job: '.$this->__getShortClassName().' had an exception');
    
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

    public function sendMailInBackground($mailTo, $mailableClass, $mailableClassParams, $invoiceID = null){
        if (env('APP_DEBUG')) {
            // Debug mode is enabled so use debugging mail instead of provided mail
            $mailTo = env("MAIL_DEBUG");
            Log::info("Dispatching mail job");
        }

        // Calculate the size of the payload
        $payloadSize = strlen(serialize([$mailTo, $mailableClass, $mailableClassParams, $invoiceID]));

        // Define the maximum allowed payload size (in bytes)
        $maxPayloadSize = 65535;

        if ($payloadSize > $maxPayloadSize) {
            // Payload size exceeds the maximum allowed size
            Log::error("Payload size exceeds the maximum allowed size. Payload not dispatched.");
            throw new \Exception('The email payload size is too large to put in the job queue. Please reduce the size of the provided params.');
        }
        
        _SendMailJob::dispatch($mailTo, $mailableClass, serialize($mailableClassParams), $this->JobRunId, $invoiceID);
    }

}