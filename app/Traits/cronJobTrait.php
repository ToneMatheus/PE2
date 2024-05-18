<?php
namespace App\Traits;

use App\Events\JobCompleted;
use App\Events\JobDispatched;
use App\Events\JobStarted;
use Carbon\Carbon;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;
use Illuminate\Support\Facades\Log;
use App\Jobs\_SendMailJob;

trait cronJobTrait
{
    private $JobRunId;
    private $LoggingLevel;

    private function __getShortClassName(){
        $className = get_class($this);
        $classNameParts = explode('\\', $className);
        return end($classNameParts);
    }

    private function __Log($logLevel, $invoiceId, $message, $detailedMessage) {
        $logLevelMap = [
            0 => "Debug",
            1 => "Info",
            2 => "Warning",
            3 => "Critical",
            4 => "Error",
        ];
        if ($logLevel < $this->LoggingLevel){
            return;
        }
    
        $logLevelString = $logLevelMap[$logLevel] ?? "Unknown";
    
        try {
            CronJobRunLog::create([
                'cron_job_run_id' => $this->JobRunId,
                'invoice_id' => $invoiceId,
                'log_level' => $logLevelString,
                'detailed_message' => $detailedMessage,
                'job_name' => $this->__getShortClassName(),
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error("Error occurred while logging: " . $e->getMessage());
        }
    }

    private function jobStart(){
        // Log that the job execution has started
        if ($this->JobRunId != null) return;

        try {
            // Create new JobRun in the database
            $jobRun = CronJobRun::create([
                'name' => class_basename($this),
                'started_at' => Carbon::now(), 
                'ended_at' => null, 
                'status' => 'Running', 
            ]);
    
            $this->JobRunId = $jobRun->id;
            event(new JobDispatched($this->JobRunId, $this->__getShortClassName()));
        } catch (\Exception $e) {
            Log::error("Error occurred while starting the job: " . $e->getMessage());
        }
    }

    private function jobCompletion($message){
        event(new JobCompleted($this->JobRunId, $this->__getShortClassName(), $message));
    }

    private function jobException($errorMessage){
        event(new JobCompleted($this->JobRunId, $this->__getShortClassName(), $errorMessage));
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

    public function logDebug($invoiceId, $message, $detailedMessage = null){
        $this->__Log(0, $invoiceId, $message, $detailedMessage);
    }

    public function logInfo($invoiceId, $message, $detailedMessage = null){
        $this->__Log(1, $invoiceId, $message, $detailedMessage);
    }

    public function logWarning($invoiceId, $message, $detailedMessage = null){
        $this->__Log(2, $invoiceId, $message, $detailedMessage);
    }

    public function logCritical($invoiceId, $message, $detailedMessage = null){
        $this->__Log(3, $invoiceId, $message, $detailedMessage);
    }

    public function logError($invoiceId, $message, $detailedMessage = null){
        $this->__Log(4, $invoiceId, $message, $detailedMessage);
    }

    public function sendMailInBackground($mailTo, $mailableClass, $mailableClassParams, $invoiceID = null){
        if (env('APP_DEBUG')) {
            // Debug mode is enabled so use debugging mail instead of provided mail
            $mailTo = env("MAIL_DEBUG");
            Log::info("Dispatching mail job");
        }
        
        Log::info($this->JobRunId);
        event(new JobDispatched($this->JobRunId, "_SendMailJob"));
        _SendMailJob::dispatch($mailTo, $mailableClass, serialize($mailableClassParams), null, null, $this->JobRunId, $invoiceID);
    }

    public function sendMailInBackgroundWithPDF($mailTo, $mailableClass, $mailableClassParams, $pdfView, $pdfParams ,$invoiceID = null){
        if (env('APP_DEBUG')) {
            // Debug mode is enabled so use debugging mail instead of provided mail
            $mailTo = env("MAIL_DEBUG");
            Log::info("Dispatching mail job");
        }
        
        event(new JobDispatched($this->JobRunId, "_SendMailJob"));
        _SendMailJob::dispatch($mailTo, $mailableClass, serialize($mailableClassParams), $pdfView, serialize($pdfParams), $this->JobRunId, $invoiceID, $this->LoggingLevel);
    }

}