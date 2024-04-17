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
use App\Traits\jobLoggerTrait;
use Carbon\Carbon;

class TemplateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, jobLoggerTrait;

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
            $JobRunId = $this->jobStart();

            $messages = [
                "this is an info message",
                "this is a warning",
                "this is a critical error",
                "oh no i did big oopsie"
            ];
            
            for ($i = 0; $i < 1000; $i++) {
                $message = $messages[array_rand($messages)];
                $this->logInfo($JobRunId, rand(1, 1000), $message);
            }

            $this->jobCompletion($JobRunId);
        } catch (\Throwable $e) {
            // Catch any throwable errors, including errors and exceptions
            $this->jobException($JobRunId, $e->getMessage());
        }
    }
}
