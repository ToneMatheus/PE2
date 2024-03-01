<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product')->insert([
            [
                'ID' => 1,
                'productName' => 'Residential tier 1',
                'unitPrice' => 0.25,
                'startDate' => '2024-01-01',
                'customerContractID' => 1
            ],
            ]);
    }
}
