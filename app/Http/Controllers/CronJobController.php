<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\CronJob;
use App\Models\CronJobRun;
use App\Models\CronJobRunLog;
use Illuminate\Support\Facades\Log;

class CronJobController extends Controller
{
    public function index(){   
        // Fetch job files in the app/Jobs directory
        $jobPath = app_path('Jobs');
        if (File::exists($jobPath) && File::isDirectory($jobPath)) {
            $files = File::files($jobPath);
            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                if (Str::endsWith($file, '.php') && !Str::startsWith($filename, '_')) {
                    // Check if the job is already in the database
                    if (CronJob::where('name', $filename)->doesntExist()) {
                        $newJob = new CronJob();
                        $newJob->name = $filename;
                        $newJob->is_enabled = false;
                        $newJob->log_level = 2;
                        $newJob->scheduled_time = "00:00:00";
                        $newJob->save();
                    }
                }
            }
        }

        $scheduledJobs = [];
        $unscheduledJobs = [];
        $scheduledJobs = CronJob::whereNotNull('interval')->get();
        $unscheduledJobs = CronJob::whereNull('interval')->get();

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
    
    public function run(Request $request, $job){
        $logLevel = $request->input('logInput');
        $jobClass = 'App\Jobs\\' . $job;
        $jobClass::dispatch($logLevel);
        return redirect()->back()->with('regularJobStatus', 'job has been run.');
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
                if (Str::endsWith($file, '.php') && !Str::startsWith($filename, '_')) {
                    $jobs[] = $filename;
                }
            }
        }

        $jobRuns = CronJobRun::query()
            ->where('name', $paramJob)
            ->orderBy('started_at', 'asc')
            ->get();

        $logCounts= collect([]);
        $jobLogs = collect([]);
        if ($jobRuns->count() > 0){
            $jobLogsQuery = CronJobRunLog::query()
                ->where('cron_job_run_id', $jobRuns->last()->id)
                ->orderBy('created_at', 'desc');

            $jobLogs = $jobLogsQuery->paginate(10);
        
            $logCounts = CronJobRunLog::query()
                ->select('log_level', DB::raw('count(*) as count'))
                ->where('cron_job_run_id', $jobRuns->last()->id)
                ->groupBy('log_level')
                ->get();
            }

        return view('cronjobs/history', compact('jobs', 'jobRuns', 'jobLogs', 'logCounts', 'paramJob'));
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
        $entries = request('entries');

        $jobRun = CronJobRun::query()
            ->where('id', $jobRunId)
            ->first();

        $jobLogsQuery = CronJobRunLog::query()
            ->where('cron_job_run_id', $jobRunId);
        
        if ($logLevel !== 'All') {
            $jobLogsQuery->where('log_level', $logLevel);
        }
        
        $jobLogs = $jobLogsQuery->paginate($entries);

        $logCounts = CronJobRunLog::query()
        ->select('log_level', DB::raw('count(*) as count'))
        ->where('cron_job_run_id', $jobRunId)
        ->groupBy('log_level')
        ->get();

        return view('cronjobs/parts/logs', compact('jobRun', 'jobLogs', 'logCounts'));
    }

    public function updateLogLevel(Request $request, $jobName)
    {
        $request->validate([
            'log_level' => 'required|numeric|min:0|max:4',
        ]);

        $job = CronJob::where('name', $jobName)->first();
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        $job->log_level = $request->input('log_level');
        $job->save();

        return response()->json(['message' => 'Log level updated successfully', 'job' => $job]);
    }

}
