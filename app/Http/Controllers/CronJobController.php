<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\CronJob;
use App\Jobs\RegularJob;
use App\Jobs\SpecialJob;

class CronJobController extends Controller
{
    public function index()
    {   
        $cronjobs = CronJob::all();
        return view('cronjobs/index', compact('cronjobs'));
    }

    public function edit($job)
    {
        $cronjob = CronJob::where('name', $job)->firstOrFail(); 
        return view('cronjobs/update', compact('cronjob'));
    }
    
    public function update(Request $request, $job)
    {
        $validatedData = $request->validate([
            'scheduled_day' => 'required|numeric|min:1|max:28',
            'scheduled_time' => 'required|date_format:H:i',
            'description' => 'nullable|string', // You may add validation for description if needed
        ]);

        // Update or create job schedule in the database
        CronJob::where('name', $job)->update($validatedData);

        return redirect()->back()->with('success', 'Schedule updated successfully');
    }

    public function run($job){
        $jobClass = 'App\Jobs\\' . $job;
        $jobClass::dispatch();
        return redirect()->back()->with('regularJobStatus', 'Regular job has been run.');
    }
    
}
