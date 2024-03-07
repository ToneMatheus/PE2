<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\RegularJob;
use App\Jobs\SpecialJob;

class JobStatusController extends Controller
{
    public function index()
    {
        $schedule = app(Schedule::class);

        $regularJobStatus = $this->isJobScheduled($schedule, 'RegularJob');
        $specialJobStatus = $this->isJobScheduled($schedule, 'SpecialJob');    

        return view('job-status');
    }

    public function runRegularJob()
    {
        RegularJob::dispatch();
        return redirect()->back()->with('regularJobStatus', 'Regular job has been run.');
    }

    public function runSpecialJob()
    {
        SpecialJob::dispatch();
        return redirect()->back()->with('specialJobStatus', 'Special job has been run.');
    }

    private function isJobScheduled($schedule, $jobName)
    {
        $events = $schedule->events();

        foreach ($events as $event) {
            if ($event->description === $jobName) {
                return true;
            }
        }

        return false;
    }
}
