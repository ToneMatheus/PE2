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

        for($i = 1; $i <= 4; $i++){
            $meters[] = [
                'id' => $i,
                'EAN' => sprintf("%018d", rand(0, 999999999999999999)),
                'type' => 'Electricity',
                'installation_date' => '2024-01-01',
                'status' => 'Installed'
            ];
        }

        DB::table('meters')->insert($meters);
    }
}
