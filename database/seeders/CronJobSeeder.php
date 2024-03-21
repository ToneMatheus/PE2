<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CronJobSeeder extends Seeder
{
    public function run()
    {
        DB::table('cron_jobs')->insert([
            [
                'name' => 'AnnualInvoiceJob',
                'scheduled_day' => 15,
                'scheduled_time' => '08:02:00'
            ],
            [
                'name' => 'MonthlyInvoiceJob',
                'scheduled_day' => 15,
                'scheduled_time' => '08:02:00'
            ],
            [
                'name' => 'WeekAdvanceReminderJob',
                'scheduled_day' => 15,
                'scheduled_time' => '08:02:00'
            ]
        ]);
    }
}
