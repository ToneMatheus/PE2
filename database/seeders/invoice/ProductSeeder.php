<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [];

        for($i = 1; $i <= 3; $i++){
            $products[] = [
                'id' => $i,
                'product_name' => 'Tier' . $i,
                'description' => null,
                'start_date' => '2024-01-01',
                'end_date' => null,
                'type' => 'Residential'
            ];
        }

        $products[] = [
            'id' => 4,
            'product_name' => 'Tier 1',
            'description' => null,
            'start_date' => '2024-01-01',
            'end_date' => null,
            'type' => 'Commercial'
        ];

        $products[] = [
            'id' => 5,
            'product_name' => 'Tier 2',
            'description' => null,
            'start_date' => '2024-01-01',
            'end_date' => null,
            'type' => 'Commercial'
        ];

        DB::table('products')->insert($products);
    }
}
