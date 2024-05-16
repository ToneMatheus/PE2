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
                'reading_value' => 900,
                'meter_id' => 9
            ],
            [
                'id' => 2,
                'reading_date' => '2024-02-01',
                'reading_value' => 1800,
                'meter_id' => 9
            ],
            [
                'id' => 3,
                'reading_date' => '2024-03-01',
                'reading_value' => 2700,
                'meter_id' => 9
            ],
            [
                'id' => 4,
                'reading_date' => '2024-04-01',
                'reading_value' => 3600,
                'meter_id' => 9
            ],
            [
                'id' => 5,
                'reading_date' => '2024-05-01',
                'reading_value' => 4500,
                'meter_id' => 9
            ],
            [
                'id' => 6,
                'reading_date' => '2024-06-01',
                'reading_value' => 5400,
                'meter_id' => 9
            ],
            [
                'id' => 7,
                'reading_date' => '2024-07-01',
                'reading_value' => 6300,
                'meter_id' => 9
            ],
            [
                'id' => 8,
                'reading_date' => '2024-08-01',
                'reading_value' => 7200,
                'meter_id' => 9
            ],
            [
                'id' => 9,
                'reading_date' => '2024-09-01',
                'reading_value' => 8100,
                'meter_id' => 9
            ],
            [
                'id' => 10,
                'reading_date' => '2024-10-01',
                'reading_value' => 9000,
                'meter_id' => 9
            ],
            [
                'id' => 11,
                'reading_date' => '2024-11-01',
                'reading_value' => 9900,
                'meter_id' => 9
            ],
            [
                'id' => 12,
                'reading_date' => '2024-12-01',
                'reading_value' => 10800,
                'meter_id' => 9
            ],
            [
                'id' => 13,
                'reading_date' => '2025-01-01',
                'reading_value' => 11700,
                'meter_id' => 9
            ],
            [
                'id' => 14,
                'reading_date' => '2025-02-01',
                'reading_value' => 12600,
                'meter_id' => 9
            ],
            [
                'id' => 15,
                'reading_date' => '2025-03-01',
                'reading_value' => 13500,
                'meter_id' => 9
            ],
            [
                'id' => 16,
                'reading_date' => '2025-04-01',
                'reading_value' => 14400,
                'meter_id' => 9
            ],
            [
                'id' => 17,
                'reading_date' => '2025-05-01',
                'reading_value' => 15300,
                'meter_id' => 9
            ],
        ]);
    }
}
