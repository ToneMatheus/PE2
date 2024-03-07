<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\RegularJob;
use App\Jobs\SpecialJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        // Run a job every month on the 12th day except for the 12th month
        $schedule->job(new RegularJob())->monthlyOn(12, '00:00')->when(function () {
            return now()->month !== 12;
        });

        // Run a special job on the 12th month
        $schedule->job(new SpecialJob())->monthlyOn(12, '00:00')->when(function () {
            return now()->month === 12;
        });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
