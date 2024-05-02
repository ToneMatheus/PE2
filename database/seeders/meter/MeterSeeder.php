<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Meter;

class MeterSeeder extends Seeder
{
    public function run(): void
    {
        $meters = [];

        for($i = 1; $i <= 8; $i++){
            $meters[] = [
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => '2024-01-01',
                'status' => 'Installed',
                'is_smart' => 0
            ];
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

        DB::table('meters')->insert($meters);
    }
}
