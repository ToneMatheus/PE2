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

        for($i=1, $id = 3; $i <= 4; $i++, $id++){
            $adds[] = [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'address_id' => $id,
                'meter_id' => $i
            ];
        }

        DB::table('meter_addresses')->insert($adds);
    }
}
