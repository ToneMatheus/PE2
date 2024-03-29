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
        ]);
    }
}
