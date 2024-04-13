<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\CronJob;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;

class CronJobController extends Controller
{
    public function index(){   
        $scheduledJobs = CronJob::all();

        // Fetch job files in the app/Jobs directory
        $unscheduledJobs = [];
        $jobPath = app_path('Jobs');
        if (File::exists($jobPath) && File::isDirectory($jobPath)) {
            $files = File::files($jobPath);
            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                if (Str::endsWith($file, '.php') && !Str::startsWith($filename, '.')) {
                    // Check if the job is already in the database
                    $existingJob = CronJob::where('name', $filename)->exists();
                    if (!$existingJob) {
                        $unscheduledJobs[] = $filename;
                    }
                }
            }
        }

        return view('cronjobs/index', compact('scheduledJobs', 'unscheduledJobs'));
    }

    public function edit_schedule($job){
        $cronjob = CronJob::where('name', $job)->first(); 
        if ($cronjob == null){
            $cronjob = new CronJob();
            $cronjob->name = $job;
        }
        return view('cronjobs/edit-schedule', compact('cronjob'));
    }

    public function store_schedule(Request $request, $job){
        //base validation rules
        $baseRules = [
            'name' => 'required|string',
            'interval' => 'required|string',
        ];
        // Dynamic validation rules based on the interval
        $interval = $request->input('interval');
        if ($interval == 'yearly') {
            $dynamicRules = [
                'scheduled_day' => 'required|numeric|min:1|max:28',
                'scheduled_month' => 'required|string',
                'scheduled_time' => 'required|string',
            ];
        } elseif ($interval == 'monthly') {
            $dynamicRules = [
                'scheduled_day' => 'required|numeric|min:1|max:28',
                'scheduled_time' => 'required|string',
            ];
        } elseif ($interval == 'daily') {
            $dynamicRules = [
                'scheduled_time' => 'required|string',
            ];
        }
        
        // Merge base rules with dynamic rules
        $mergedarray = array_merge($baseRules, $dynamicRules);
        $validatedData = $request->validate($mergedarray);
        
        $cronjob = CronJob::where('name', $job)->first();
        if ($cronjob == null){
            CronJob::create($validatedData);
        }
        else{
            // Update job schedule in the database
            $cronjob->update([
                'interval' => $validatedData['interval'],
                'scheduled_day' => $validatedData['scheduled_day'] ?? null,
                'scheduled_month' => $validatedData['scheduled_month'] ?? null,
                'scheduled_time' => $validatedData['scheduled_time'] ?? null
            ]);
        }
    
        return redirect()->back()->with('success', 'Schedule updated successfully');
    }

    public function toggle_schedule($job){
        $cronjob = CronJob::where('name', $job)->first(); 
        if ($cronjob != null){
            $cronjob->is_enabled = !$cronjob->is_enabled;
            $cronjob->save();
        }
        
        return redirect()->back()->with('success', 'Schedule updated successfully');
    }
    
    public function run($job){
        $jobClass = 'App\Jobs\\' . $job;
        $jobClass::dispatch();
        return redirect()->back()->with('regularJobStatus', 'Regular job has been run.');
    }
    
    public function showHistory()
    {
        $paramJob = request('job');
        
        // Fetch job files in the app/Jobs directory
        $jobs = [];
        $jobPath = app_path('Jobs');
        if (File::exists($jobPath) && File::isDirectory($jobPath)) {
            $files = File::files($jobPath);
            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                if (Str::endsWith($file, '.php') && !Str::startsWith($filename, '.')) {
                    $jobs[] = $filename;
                }
            }
        }

        $jobRuns = CronJobRun::query()
            ->where('name', $paramJob)
            ->orderBy('started_at', 'asc')
            ->get();

        $jobLogs = [];
        if ($jobRuns->count() > 0){
            $jobLogs = CronJobRunLog::query()
                ->where('cron_job_run_id', $jobRuns->last()->id)
                ->get();
        }

        return view('cronjobs/history', compact('jobs', 'jobRuns', 'jobLogs', 'paramJob'));
    }

    public function getJobRuns(Request $request){
        $job = $request->input('job');

        $jobRuns = CronJobRun::where('name', $job)
            ->orderBy('started_at', 'asc')
            ->get();

        $formattedJobRuns = $jobRuns->map(function ($jobRun) {
            return [
                'id' => $jobRun->id,
                'started_at' => $jobRun->started_at
            ];
        });

        return response()->json($formattedJobRuns);
    }

    public function getJobRunLogs(){
        $jobRunId = request('jobRunId');
        $logLevel = request('LogLevel');
        
        $query = CronJobRunLog::query()
            ->where('cron_job_run_id', $jobRunId);
        
        if ($logLevel !== 'All') {
            $query->where('log_level', $logLevel);
        }
        
        $jobLogs = $query->get();
        
        return view('cronjobs/parts/logs', compact('jobLogs'));
    }
}
