<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tariff;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        $tariff = [
            [
                'ID' => 1,
                'name' => 'Tier 1',
                'type' => 'Commercial',
                'rangeMin' => 0,
                'rangeMax' => 1000,
                'rate' => 0.25
            ],
            [
                'ID' => 2,
                'name' => 'Tier 1',
                'type' => 'Residential',
                'rangeMin' => 0,
                'rangeMax' => 500,
                'rate' => 0.25
            ]
            ];

            foreach ($tariff as $tariffData){
                Tariff::create($tariffData);
            }
    }
}
