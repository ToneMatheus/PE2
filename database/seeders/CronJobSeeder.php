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
                'name' => 'InvoicerunJob',
                'interval' => 'daily',
                'scheduled_day' => 15,
                'scheduled_month' => null,
                'scheduled_time' => '22:30:00'
            ]
        ]);
    }
}
