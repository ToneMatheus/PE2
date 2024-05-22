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
                'name' => 'MeterSchedule',
                'interval' => 'daily',
                'log_level' => 3,
                'scheduled_day' => null,
                'scheduled_month' => null,
                'scheduled_time' => '00:00:00',
                'log_level' => 1,
            ],
            [
                'name' => 'MeterAllocation',
                'interval' => 'daily',
                'log_level' => 3,
                'scheduled_day' => null,
                'scheduled_month' => null,
                'scheduled_time' => '00:00:10',
                'log_level' => 1,
            ],
            [
                'name' => 'InvoiceRunJob',
                'interval' => 'daily',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '10:00:00',
                'log_level' => 1,
            ],
            [
                'name' => 'EnterIndexCustomerJob',
                'interval' => 'daily',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '10:00:00',
                'log_level' => 1,
            ],
            [
                'name' => 'MoveOutJob',
                'interval' => 'daily',
                'scheduled_day' => null,
                'scheduled_month' => null,
                'scheduled_time' => '10:00:00',
                'log_level' => 1,
            ],
            [
                'name' => 'AnnualInvoiceJob',
                'interval' => 'yearly',
                'scheduled_day' => 15,
                'scheduled_month' => 6,
                'scheduled_time' => '10:15:00'
            ],
            [
                'name' => 'MonthlyInvoiceJob',
                'interval' => 'monthly',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '22:30:00'
            ],
            [
                'name' => 'ValidationJob',
                'interval' => 'daily',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '22:30:00'
            ]
        ]);
    }
}
