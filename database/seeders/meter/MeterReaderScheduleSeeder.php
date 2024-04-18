<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeterReaderScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $adds = [];

        for($i=3; $i <= 5; $i++){
            $adds[] = [
                'employee_profile_id' => 1,
                'reading_date' => '2024-03-21',
                'meter_id' => $i,
                'status' => 'unread'
            ];
        }

        for($i=6; $i <= 8; $i++){
            $adds[] = [
                'employee_profile_id' => 2,
                'reading_date' => '2024-03-21',
                'meter_id' => $i,
                'status' => 'unread'
            ];
        }

        DB::table('meter_reader_schedules')->insert($adds);
    }
}