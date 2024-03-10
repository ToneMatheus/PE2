<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Contract_product;

class ContractProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contract_products')->insert([
            [
                'id' => 1,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 1,
                'tariff_id' => null,
                'product_id' => 1,
            ],
            [
                'id' => 2,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 2,
                'tariff_id' => null,
                'product_id' => 5,
            ],
            [
                'id' => 3,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 3,
                'tariff_id' => null,
                'product_id' => 1,
            ],
            ]);
    }
}
