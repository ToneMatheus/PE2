<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('discounts')->insert([
            'contract_product_id' => 2,
            'range_min' => null,
            'range_max' => null,
            'rate' => 0.26,
            'start_date' => '2025-02-01',
            'end_date' => '2025-02-15',
        ]);

        DB::table('discounts')->insert([
            'contract_product_id' => 1,
            'range_min' => null,
            'range_max' => null,
            'rate' => 0.23,
            'start_date' => '2024-01-01',
        ]);
    }
}
