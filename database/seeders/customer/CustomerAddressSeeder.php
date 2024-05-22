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
        for($i=1; $i <= 5; $i++) {
            DB::table('customer_addresses')->insert([
                [
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'user_id' => $i,
                    'address_id' => $i
                ]
            ]);
        }

        for($i=5; $i <= 12; $i++) {
            DB::table('customer_addresses')->insert([
                [
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'user_id' => $i, // 12
                    'address_id' => $i + 1 // 13
                ]
            ]);
        }

        DB::table('customer_addresses')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => 10,
                'address_id' => 14
            ]
        ]);

        for($i=13; $i <= 18; $i++) {
            DB::table('customer_addresses')->insert([
                [
                    'start_date' => '2024-01-01',
                    'end_date' => null,
                    'user_id' => $i, // 18
                    'address_id' => $i + 2 // 20
                ]
            ]);
        }

        DB::table('customer_addresses')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => 19,
                'address_id' => 13
            ]
        ]);

        DB::table('customer_addresses')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => 20,
                'address_id' => 16
            ]
        ]);

        DB::table('customer_addresses')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => 10,
                'address_id' => 21
            ]
        ]);
    }
}
