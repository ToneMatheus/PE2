<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer_address;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {
        $adds = [];
        for($i=3; $i <= 5; $i++) {
            $adds[] = [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => $i,
                'address_id' => $i
            ];
        }

        $adds[] = [
            'start_date' => '2024-01-01',
            'end_date' => null,
            'user_id' => 5,
            'address_id' => 6
        ];

        DB::table('customer_addresses')->insert($adds);
    }
}
