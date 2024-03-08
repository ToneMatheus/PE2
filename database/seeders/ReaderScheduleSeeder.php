<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReaderScheduleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('meterReaderSchedules')->insert([
            [
                'ID' => 1,
                'readingDate' => '2024-01-01',
                'status' => 1,
                'employeeID' => 1,
                'meterID' => 1,
            ],
            [
                'ID' => 2,
                'readingDate' => '2024-01-01',
                'status' => 0,
                'employeeID' => 1,
                'meterID' => 2,
            ],
            [
                'ID' => 3,
                'readingDate' => '2024-01-03',
                'status' => 0,
                'employeeID' => 1,
                'meterID' => 3,
            ],
            ]);
    }
}
