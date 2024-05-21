<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Meter_address;

class MeterAddressSeeder extends Seeder
{
    public function run(): void
    {
        $adds = [];

        for($i=3; $i <= 10; $i++){
            $adds[] = [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => $i,
                'meter_id' => $i-2
            ];
        }
        for($i=3; $i <= 10; $i++){
            $adds[] = [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => $i,
                'meter_id' => $i-2+8
            ];
        }

        DB::table('meter_addresses')->insert($adds);
    }
}
