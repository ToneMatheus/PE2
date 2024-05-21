<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\MeterAllocation;
use App\Jobs\MeterSchedule;
use App\Models\CronJob;
use App\Models\Ticket;
use App\Notifications\TicketOpenNotification;

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
            if ($cronjob->is_enabled){
                $jobClass = 'App\Jobs\\' .  $cronjob->name;
    
                // Extract hour and minutes from the scheduled_time string
                $timeParts = explode(':', $cronjob->scheduled_time);
                $hour = $timeParts[0];
                $minute = $timeParts[1];
                
                switch ($cronjob->interval) {
                    case 'daily':
                        $schedule->job(new $jobClass($cronjob->log_level))->dailyAt($hour . ':' . $minute);
                        break;
                    case 'monthly':
                        $schedule->job(new $jobClass($cronjob->log_level))->monthlyOn($cronjob->scheduled_day, $hour . ':' . $minute);
                        break;
                    case 'yearly':
                        $schedule->job(new $jobClass($cronjob->log_level))->yearlyOn($cronjob->scheduled_month, $cronjob->scheduled_day, $hour . ':' . $minute);
                        break;
                }
            }
        }

        $schedule->call(function () {
            $tickets = Ticket::where('created_at', '<=', now()->subMinutes(10))->get();
            $roleId = 2; // ID of the role to notify EMPLOYEE
        
            foreach ($tickets as $ticket) {
                $ticket->notify(new TicketOpenNotification($ticket, $roleId));
            }
        })->daily();
    }

    protected function meter_allocation(Schedule $schedule): void
    {
        $schedule->job(new MeterAllocation())->everyMinute();
    }

    protected function meter_schedule(Schedule $schedule): void
    {
        $schedule->job(new MeterSchedule())->everyMinute();
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
