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

        $exampleInstallation = '2021-01-01';

        $expecting_reading = 1;

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
        for($i = 9; $i <= 16; $i++){
            $meters[] = [
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => '2024-01-01',
                'status' => 'Installed',
                'is_smart' => 1
            ];
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

        for($i = 11; $i <= 17; $i++){
            DB::table('meters')->insert([
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => $exampleInstallation,
                'status' => 'Installed',
                'is_smart' => 0,
                'expecting_reading' => $expecting_reading
            ]);
        }
    }
}
