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
                'type' => 'Residential',
                'rangeMin' => 0,
                'rangeMax' => 500,
                'rate' => 0.25,
            ],
            [
                'ID' => 2,
                'type' => 'Residential',
                'rangeMin' => 500,
                'rangeMax' => 1000,
                'rate' => 0.28,
            ],
            [
                'ID' => 3,
                'type' => 'Residential',
                'rangeMin' => 1001,
                'rangeMax' => null,
                'rate' => 0.32,
            ],
            [
                'ID' => 4,
                'type' => 'Commercial',
                'rangeMin' => 0,
                'rangeMax' => 1000,
                'rate' => 0.25,
            ],
            [
                'ID' => 5,
                'type' => 'Discount',
                'rangeMin' => null,
                'rangeMax' => null,
                'rate' => 0.24,
            ],
        ]);
    }
}

?>