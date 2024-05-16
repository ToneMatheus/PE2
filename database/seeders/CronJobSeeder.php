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
                'interval' => 'daily',
                'scheduled_day' => null,
                'scheduled_month' => null,
                'scheduled_time' => '00:00:00',
                'log_level' => 1,
            ],
            [
                'name' => 'MeterAllocation',
                'interval' => 'daily',
                'scheduled_day' => null,
                'scheduled_month' => null,
                'scheduled_time' => '00:00:10',
                'log_level' => 1,
            ],
            [
                'name' => 'EnterIndexCustomerJob',
                'interval' => 'daily',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '10:00:00'
            ],
            [
                'name' => 'MoveOutJob',
                'interval' => 'daily',
                'scheduled_day' => null,
                'scheduled_month' => null,
                'scheduled_time' => '10:00:00'
            ],
            [
                'name' => 'AnnualInvoiceJob',
                'interval' => 'yearly',
                'scheduled_day' => 15,
                'scheduled_month' => 6,
                'scheduled_time' => '10:15:00',
                'log_level' => 1,
            ],
            [
                'name' => 'MonthlyInvoiceJob',
                'interval' => 'monthly',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '22:30:00',
                'log_level' => 1,
            ],
            [
                'name' => 'InvoicerunJob',
                'interval' => 'daily',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '22:30:00',
                'log_level' => 1,
            ]
        ]);
    }
}
