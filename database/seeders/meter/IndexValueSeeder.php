<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndexValueSeeder extends Seeder
{
    public function run()
    {
        DB::table('index_values')->insert([
            [
                'id' => 1,
                'reading_date' => '2024-01-01',
                'reading_value' => 375,
                'meter_id' => 1,
            ],
            [
                'id' => 2,
                'reading_date' => '2024-02-01',
                'reading_value' => 410,
                'meter_id' => 1,
            ],
            [
                'id' => 3,
                'reading_date' => '2024-03-01',
                'reading_value' => 440,
                'meter_id' => 1,
            ]
        ]);

        DB::table('index_values')->insert([
            [
                'id' => 4,
                'reading_date' => '2024-01-01',
                'reading_value' => 1555,
                'meter_id' => 2,
            ],
            [
                'id' => 5,
                'reading_date' => '2024-02-01',
                'reading_value' => 1600,
                'meter_id' => 2,
            ],
            [
                'id' => 6,
                'reading_date' => '2024-03-01',
                'reading_value' => 1720,
                'meter_id' => 2,
            ]
        ]);

        DB::table('index_values')->insert([
            [
                'id' => 7,
                'reading_date' => '2024-01-01',
                'reading_value' => 355,
                'meter_id' => 3,
            ],
            [
                'id' => 8,
                'reading_date' => '2024-02-01',
                'reading_value' => 410,
                'meter_id' => 3,
            ],
            [
                'id' => 9,
                'reading_date' => '2024-03-01',
                'reading_value' => 440,
                'meter_id' => 3,
            ]
        ]);

        DB::table('index_values')->insert([
            [
                'id' => 10,
                'reading_date' => '2024-01-01',
                'reading_value' => 285,
                'meter_id' => 4,
            ],
            [
                'id' => 11,
                'reading_date' => '2024-02-01',
                'reading_value' => 300,
                'meter_id' => 4,
            ],
            [
                'id' => 12,
                'reading_date' => '2024-03-01',
                'reading_value' => 310,
                'meter_id' => 4,
            ]
        ]);
    }
}