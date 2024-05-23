<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Meter;
use Carbon\Carbon;

class MeterSeeder extends Seeder
{
    public function run(): void
    {
        $oneYearDate = Carbon::now()->subYears(1)->toDateString();
        $twoYearsDate = Carbon::now()->subYears(2)->toDateString();
        $threeYearsDate = Carbon::now()->subYears(3)->toDateString();
        $lazyDate = Carbon::now()->subYears(3)->subWeeks(1)->toDateString();
        $veryLazyDate = Carbon::now()->subYears(3)->subWeeks(2)->toDateString();


        $expecting_reading = 0;

        for($i = 1; $i <= 6; $i++){
            DB::table('meters')->insert([
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => '2024-01-01',
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => $expecting_reading
            ]);
        }

        for($i = 7; $i <= 8; $i++){
            DB::table('meters')->insert([
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $oneYearDate,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => $expecting_reading
            ]);
        }

        DB::table('meters')->insert([
            'id' => 9,
            'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
            'type' => 'Electricity',
            'installation_date' => $oneYearDate,
            'status' => 'Installed',
            'is_smart' => 1,
            'expecting_reading' => $expecting_reading
        ]);

        DB::table('meters')->insert([
            'id' => 10,
            'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
            'type' => 'Electricity',
            'installation_date' => '2024-01-01',
            'status' => 'Installed',
            'is_smart' => 0,
            'expecting_reading' => $expecting_reading
        ]);

        for($i = 11; $i <= 13; $i++){
            DB::table('meters')->insert([
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $twoYearsDate,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => $expecting_reading
            ]);
        }

        DB::table('meters')->insert([
            [
                'id' => 14,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $threeYearsDate,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => $expecting_reading
            ],
            [
                'id' => 15,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $lazyDate,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => 1
            ],
            [
                'id' => 16,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $veryLazyDate,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => 1
            ],
            [
                'id' => 17,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $threeYearsDate,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => $expecting_reading
            ]
        ]);
    }
}
