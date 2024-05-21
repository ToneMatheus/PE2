<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MeterReaderScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $adds = [];
        $today = Carbon::now()->toDateString();

        $adds[] = [
            'employee_profile_id' => 10,
            'reading_date' => $today,
            'meter_id' => 3,
            'status' => 'unread',
            'priority' => 1
        ];

        for($i=4; $i <= 5; $i++){
            $adds[] = [
                'employee_profile_id' => 10,
                'reading_date' => $today,
                'meter_id' => $i,
                'status' => 'unread',
                'priority' => 0
            ];
        }

        for($i=6; $i <= 8; $i++){
            $adds[] = [
                'employee_profile_id' => 11,
                'reading_date' => $today,
                'meter_id' => $i,
                'status' => 'unread',
                'priority' => 0
            ];
        }

        DB::table('meter_reader_schedules')->insert($adds);
    }
}