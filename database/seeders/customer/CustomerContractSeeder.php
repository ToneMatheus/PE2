<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer_contract;

class CustomerContractSeeder extends Seeder
{
   public function run(): void
    {
        $contracts = [];

        for($i=1, $id = 3; $i <= 3; $i++, $id++){
            $contracts[] = [
                'id' => $i,
                'user_id' => $id,
                'start_date' => '2022-01-01',
                'end_date' => null,
                'type' => 'Standard',
                'price' => 1000,
                'status' => 'Active'
            ];
        }

        $contracts[] = [
            'id' => 4,
            'user_id' => 6,
            'start_date' => '2025-01-01',
            'end_date' => null,
            'type' => 'Standard',
            'price' => 1000,
            'status' => 'Active'
        ];

        $contracts[] = [
            'id' => 5,
            'user_id' => 7,
            'start_date' => '2024-12-25',
            'end_date' => null,
            'type' => 'Standard',
            'price' => 1000,
            'status' => 'Active'
        ];

        $contracts[] = [
            'id' => 6,
            'user_id' => 8,
            'start_date' => '2024-12-04',
            'end_date' => null,
            'type' => 'Standard',
            'price' => 1000,
            'status' => 'Active'
        ];

        DB::table('customer_contracts')->insert($contracts);
    }
}
