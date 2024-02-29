<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $product = [
            [
                'ID' => 1,
                'productName' => 'Electricity',
                'unitPrice' => 0.28,
                'startDate' => '2024-01-01',
                'customerContractID' => 1
            ],
            [
                'ID' => 2,
                'productName' => 'Electricity',
                'unitPrice' => 0.25,
                'startDate' => '2024-01-01',
                'customerContractID' => 2
            ]
            ];

            foreach ($product as $productData){
                Product::create($productData);
            }
    }
}
