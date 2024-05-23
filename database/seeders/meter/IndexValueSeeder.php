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
                'meter_id' => 1
            ],
            [
                'id' => 2,
                'reading_date' => '2023-01-01',
                'reading_value' => 6500,
                'meter_id' => 2
            ],
            [
                'id' => 3,
                'reading_date' => '2023-01-01',
                'reading_value' => 3300,
                'meter_id' => 3
            ],
            [
                'id' => 4,
                'reading_date' => '2023-01-01',
                'reading_value' => 3400,
                'meter_id' => 4
            ],
            [
                'id' => 5,
                'reading_date' => '2024-01-01',
                'reading_value' => 3300,
                'meter_id' => 1
            ],
            [
                'id' => 6,
                'reading_date' => '2024-01-01',
                'reading_value' => 6600,
                'meter_id' => 2
            ],
            [
                'id' => 7,
                'reading_date' => '2024-01-01',
                'reading_value' => 3400,
                'meter_id' => 3
            ],
            [
                'id' => 8,
                'reading_date' => '2024-01-01',
                'reading_value' => 3200,
                'meter_id' => 4
            ],
            [
                'id' => 9,
                'reading_date' => '2025-01-01',
                'reading_value' => 3400,
                'meter_id' => 1
            ],
            [
                'id' => 11,
                'reading_date' => '2025-01-01',
                'reading_value' => 3500,
                'meter_id' => 3
            ],
            [
                'id' => 12,
                'reading_date' => '2025-01-01',
                'reading_value' => 3100,
                'meter_id' => 4
            ],
            [
                'id' => 13,
                'reading_date' => '2024-01-01',
                'reading_value' => 900,
                'meter_id' => 9
            ],
            [
                'id' => 14,
                'reading_date' => '2024-02-01',
                'reading_value' => 1800,
                'meter_id' => 9
            ],
            [
                'id' => 15,
                'reading_date' => '2024-03-01',
                'reading_value' => 2700,
                'meter_id' => 9
            ],
            [
                'id' => 16,
                'reading_date' => '2024-04-01',
                'reading_value' => 3600,
                'meter_id' => 9
            ],
            [
                'id' => 17,
                'reading_date' => '2024-05-01',
                'reading_value' => 4500,
                'meter_id' => 9
            ],
            [
                'id' => 18,
                'reading_date' => '2024-06-01',
                'reading_value' => 5400,
                'meter_id' => 9
            ],
            [
                'id' => 19,
                'reading_date' => '2024-07-01',
                'reading_value' => 6300,
                'meter_id' => 9
            ],
            [
                'id' => 20,
                'reading_date' => '2024-08-01',
                'reading_value' => 7200,
                'meter_id' => 9
            ],
            [
                'id' => 21,
                'reading_date' => '2024-09-01',
                'reading_value' => 8100,
                'meter_id' => 9
            ],
            [
                'id' => 22,
                'reading_date' => '2024-10-01',
                'reading_value' => 9000,
                'meter_id' => 9
            ],
            [
                'id' => 23,
                'reading_date' => '2024-11-01',
                'reading_value' => 9900,
                'meter_id' => 9
            ],
            [
                'id' => 24,
                'reading_date' => '2024-12-01',
                'reading_value' => 10800,
                'meter_id' => 9
            ],
            [
                'id' => 25,
                'reading_date' => '2025-01-01',
                'reading_value' => 11700,
                'meter_id' => 9
            ],
            [
                'id' => 26,
                'reading_date' => '2025-02-01',
                'reading_value' => 12600,
                'meter_id' => 9
            ],
            [
                'id' => 27,
                'reading_date' => '2025-03-01',
                'reading_value' => 13500,
                'meter_id' => 9
            ],
            [
                'id' => 28,
                'reading_date' => '2025-04-01',
                'reading_value' => 14400,
                'meter_id' => 9
            ],
            [
                'id' => 29,
                'reading_date' => '2025-05-01',
                'reading_value' => 15300,
                'meter_id' => 9
            ],
        ]);

        // smart meter readings
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $year = '2024-0'.$i.'-01';
            }
            else {
                $year = '2024-'.$i.'-01';
            }

            DB::table('index_values')->insert([
                [
                    'reading_date' => $year,
                    'reading_value' => $i * 900,
                    'meter_id' => 9
                ]
            ]);
        }

        for ($i = 10; $i <= 17; $i++) {
            $init_meter_value = rand(2500, 3000);
            $second_meter_value = $init_meter_value + rand(100, 300);
            $third_meter_value = $second_meter_value + rand(100, 300);

            DB::table('index_values')->insert([
                [
                    'reading_date' => '2023-01-01',
                    'reading_value' => $init_meter_value,
                    'meter_id' => $i
                ],
                [
                    'reading_date' => '2024-01-01',
                    'reading_value' => $second_meter_value,
                    'meter_id' => $i
                ],
                [
                    'reading_date' => '2025-01-01',
                    'reading_value' => $third_meter_value,
                    'meter_id' => $i
                ]
            ]);
        }
    }
}
