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
                'tariffID' =>2
            ],
            [
                'ID' => 3,
                'startDate' => '2024-01-01',
                'productID' => 3,
                'tariffID' => 3
            ],
            [
                'ID' => 4,
                'startDate' => '2024-01-01',
                'productID' => 4,
                'tariffID' => 4
            ],
            ]);
    }
}
