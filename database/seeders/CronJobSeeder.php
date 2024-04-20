<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CronJobSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cron_jobs')->insert([
            [
                'name' => 'MeterSchedule',
                'description' => 'Adds meter to schedule',
                'scheduled_day' => 1,
                'scheduled_time' => '00:00:00',
            ],
            [
                'name' => 'MeterAllocation',
                'description' => 'Allocates meter to employees',
                'scheduled_day' => 1,
                'scheduled_time' => '00:00:00',
            ],
        ]);
    }
}
