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
                'reading_date' => '2023-01-01',
                'reading_value' => 3200,
                'meter_id' => 4
            ],
            [
                'id' => 2,
                'reading_date' => '2023-01-01',
                'reading_value' => 3300,
                'meter_id' => 5
            ],
            [
                'id' => 3,
                'reading_date' => '2023-01-01',
                'reading_value' => 5000,
                'meter_id' => 6
            ],
            [
                'id' => 4,
                'reading_date' => '2023-01-01',
                'reading_value' => 2000,
                'meter_id' => 7
            ],
            [
                'id' => 5,
                'reading_date' => '2024-01-01',
                'reading_value' => 3300,
                'meter_id' => 4
            ],
            [
                'id' => 6,
                'reading_date' => '2024-01-01',
                'reading_value' => 5500,
                'meter_id' => 6
            ],
            [
                'id' => 7,
                'reading_date' => '2024-01-01',
                'reading_value' => 2050,
                'meter_id' => 7
            ],
            [
                'id' => 8,
                'reading_date' => '2024-01-01',
                'reading_value' => 3700,
                'meter_id' => 5
            ],
            [
                'id' => 9,
                'reading_date' => '2025-01-01',
                'reading_value' => 3400,
                'meter_id' => 4
            ],
            [
                'id' => 10,
                'reading_date' => '2025-01-01',
                'reading_value' => 3900,
                'meter_id' => 5
            ],
            [
                'id' => 11,
                'reading_date' => '2025-01-01',
                'reading_value' => 6100,
                'meter_id' => 6
            ],
            [
                'id' => 12,
                'reading_date' => '2025-01-01',
                'reading_value' => 2100,
                'meter_id' => 7
            ]
        ]);
    }
}
