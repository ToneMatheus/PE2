<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\cronJobTrait;

class TemplateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($LogLevel = null)
    {
        $this->LoggingLevel = $LogLevel;
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
            $this->jobStart();
            
            for ($i = 0; $i < 2; $i++) {
                $this->logInfo(null, "Info message $i");
                $this->logDebug(null, "Debug message $i");
                $this->logCritical(null, "Critical error message $i");
                $this->logWarning(null, "Warning message $i");
                $this->logError(null, "Error message $i");
            }

            $this->jobCompletion("Succesfully completed this job");
        } catch (\Throwable $e) {
            // Catch any throwable errors, including errors and exceptions
            $this->jobException($e->getMessage());
        }
    }
}
