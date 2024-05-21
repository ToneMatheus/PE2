<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class WeeklyActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->format('Y-m-d');
        DB::table('employee_weekly_reports')->insert([
            [  
                'employee_profile_id' => 2,
                'summary' => 'This is the summary for the first report.',
                'tasks_completed' => 'Completed Task A, Task B',
                'upcoming_tasks' => 'Task C, Task D',
                'challenges' => 'Facing challenges in Task E',
                'submission_date' => $now
            ],
            [  
                'employee_profile_id' => 3,
                'summary' => 'This is the summary for the first report.',
                'tasks_completed' => 'Completed Task X',
                'upcoming_tasks' => 'Task Y',
                'challenges' => 'No challenges',
                'submission_date' => $now
            ],
            [  
                'employee_profile_id' => 4,
                'summary' => 'This is the summary for the first report.',
                'tasks_completed' => 'Completed Task X',
                'upcoming_tasks' => 'Task Y',
                'challenges' => 'No challenges',
                'submission_date' => $now
            ],
        ]);
    }
}
