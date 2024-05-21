<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndexValueSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 8; $i++) {
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
