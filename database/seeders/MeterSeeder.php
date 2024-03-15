<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReaderScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $meterID = DB::table('meter')->insertGetId([
            //'ID' => 1,
            'EAN' => 540000000000000018,
            'type' => 'Gas',
            'installationDate' => '2021-01-01',
            'status' => 'Installed',
        ]);

        DB::table('addressMeter')->insert([
        [
            'startDate' => '2024-01-01',
            'endDate' => '2024-02-01',
            'addressID' => 1,
            'meterID' => $meterID,
        ]
        ]);

        DB::table('meterReaderSchedules')->insert([
        [
            'readingDate' => '2024-01-01',
            'status' => 'NotChecked',
            'employeeID' => 1,
            'meterID' => $meterID,
        ]
        ]);

        DB::table('indexValues')->insert([
        [
            'readingDate' => '2022-01-05',
            'readingValue' => 'NotChecked',
            'meterID' => $meterID,
        ],
        [
            'readingDate' => '2023-01-06',
            'status' => 'NotChecked',
            'meterID' => $meterID,
        ],
        ]);

        DB::table('consumption')->insert([
            [
                'startDate' => '2022-01-05',
                'endDate' => 'NotChecked',
                'consumptionValue' => 250,
                'previousIndexID' => 1,
                'currentIndexID' => 1,
            ]
            ]);
    }
}
