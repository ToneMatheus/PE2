<?php

namespace App\Listeners;

use App\Events\JobDispatched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrackJobDispatch
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
        
        // Increment the count of jobs with this identifier in the queue
        Cache::increment("job_".$jobRunId."_count");

        if (config('app.debug')){
            Log::debug("event: $jobName dispatched id:$jobRunId count:". Cache::get("job_".$jobRunId."_count"));
        }
    }
}
