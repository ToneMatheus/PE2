<?php

namespace App\Listeners;

use App\Mail\JobDoneNotification;
use App\Models\CronJobRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TrackJobCompletion
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {   
        $jobRunId = $event->jobRunId;
        $jobName = $event->jobName;

        Cache::decrement("job_count_$jobRunId");

        if (config('app.debug')){
            Log::debug("event: $jobName completed id:$jobRunId count:". Cache::get("job_count_$jobRunId"));
        }

        if (Cache::get("job_count_$jobRunId") <= 0) {
            cache::forget("job_count_$jobRunId");
           
            $this->jobCompletion($jobRunId, $event->jobCompletionMessage);
        }
    }

    private function jobCompletion($jobRunId, $message){
        // Log that the job execution has completed
        $job = CronJobRun::find($jobRunId);
        $job->ended_at = now();

        if (empty($job->error_message)) {
            $job->status = 'Completed';
            $job->error_message = $message;
        } 
        else {
            $job->status = 'Failed';
        }
        $job->save();
        
        Mail::to(env("MAIL_DEBUG"))->send(new JobDoneNotification($job->name));
        Log::info("I am done with all the subjobs sending out notification.");
    }
}
