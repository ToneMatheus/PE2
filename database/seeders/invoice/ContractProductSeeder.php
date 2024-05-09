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
                'product_id' => 1,
                'meter_id' => 1,
            ],
            [
                'id' => 2,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 2,
                'product_id' => 5,
                'meter_id' => 2,
            ],
            [
                'id' => 3,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 3,
                'product_id' => 1,
                'meter_id' => 3,
            ],
            [
                'id' => 4,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 3,
                'product_id' => 2,
                'meter_id' => 4,
            ],
            [
                'id' => 5,
                'start_date' => '2025-01-01',
                'customer_contract_id' => 4,
                'product_id' => 1,
                'meter_id' => 5,
            ],
            [
                'id' => 6,
                'start_date' => '2024-12-25',
                'customer_contract_id' => 5,
                'product_id' => 1,
                'meter_id' => 6,
            ],
            [
                'id' => 7,
                'start_date' => '2024-12-04',
                'customer_contract_id' => 6,
                'product_id' => 1,
                'meter_id' => 7,
            ],
            [
                'id' => 9,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 1,
                'product_id' => 1,
                'meter_id' => 9,
            ],
            [
                'id' => 10,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 2,
                'product_id' => 5,
                'meter_id' => 10,
            ],
            [
                'id' => 11,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 3,
                'product_id' => 1,
                'meter_id' => 11,
            ],
            [
                'id' => 12,
                'start_date' => '2024-01-01',
                'customer_contract_id' => 3,
                'product_id' => 2,
                'meter_id' => 12,
            ],
            [
                'id' => 13,
                'start_date' => '2025-01-01',
                'customer_contract_id' => 4,
                'product_id' => 1,
                'meter_id' => 13,
            ],
            [
                'id' => 14,
                'start_date' => '2024-12-25',
                'customer_contract_id' => 5,
                'product_id' => 1,
                'meter_id' => 14,
            ],
            [
                'id' => 15,
                'start_date' => '2024-12-04',
                'customer_contract_id' => 6,
                'product_id' => 1,
                'meter_id' => 15,
            ],
            ]);
    }
}
