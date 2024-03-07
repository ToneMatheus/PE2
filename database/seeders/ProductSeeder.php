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
                'type' => 'Residential'
            ],
            [
                'ID' => 2,
                'productName' => 'Tier 2',
                'startDate' => '2024-01-01',
                'type' => 'Residential'
            ],
            [
                'ID' => 3,
                'productName' => 'Tier 3',
                'startDate' => '2024-01-01',
                'type' => 'Residential'
            ],
            [
                'ID' => 4,
                'productName' => 'Tier 1',
                'startDate' => '2024-01-01',
                'type' => 'Commercial'
            ],
            ]);
    }
}
