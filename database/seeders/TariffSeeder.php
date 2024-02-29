<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tariff')->insert([
            [
                'ID' => 1,
                'name' => 'Tier 1',
                'type' => 'Commercial',
                'rangeMin' => 0,
                'rangeMax' => 1000,
                'rate' => 0.25,
                'consumptionID' => 1
            ],
            [
                'ID' => 2,
                'name' => 'Tier 1',
                'type' => 'Residential',
                'rangeMin' => 0,
                'rangeMax' => 500,
                'rate' => 0.25,
                'consumptionID' => 2
            ]
        ]);
    }
}

?>