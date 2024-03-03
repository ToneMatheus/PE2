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
                'productName' => 'Tier 1',
                'startDate' => '2024-01-01',
                'customerContractID' => 1,
                'type' => 'Residential'
            ],
            ]);
    }
}
