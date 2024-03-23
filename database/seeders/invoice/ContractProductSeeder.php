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
                'meter_id' => 3,
            ],
            ]);
    }
}
