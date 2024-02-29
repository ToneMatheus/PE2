<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductTariff;

class ProductTariffSeeder extends Seeder
{
    public function run(): void
    {
        $productTariff = [
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
            ];

            foreach ($productTariff as $productTariffData){
                ProductTariff::create($productTariffData);
            }
    }
}
