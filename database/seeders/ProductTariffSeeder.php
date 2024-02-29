<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTariffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productTariff')->insert([
            [
                'ID' => 1,
                'startDate' => '2024-01-01',
                'productID' => 1,
                'tariffID' => 1
            ],
            [
                'ID' => 2,
                'startDate' => '2024-01-01',
                'productID' => 2,
                'tariffID' => 2
            ]
            ]);
    }
}
