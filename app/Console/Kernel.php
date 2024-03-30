<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\CronJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        // Fetch job schedules from the database
        $cronjobs = CronJob::all();
        foreach ($cronjobs as $cronjob) {
            $jobClass = 'App\Jobs\\' .  $cronjob->name;

            // Extract hour and minutes from the scheduled_time string
            $timeParts = explode(':', $cronjob->scheduled_time);
            $hour = $timeParts[0];
            $minute = $timeParts[1];

            $schedule->job(new $jobClass())->monthlyOn($cronjob->scheduled_day, $hour . ':' . $minute);
        }



        //to automate the creation of payslips
       // $schedule->command('insert:data')->daily(); 
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
