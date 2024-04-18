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
        for($i=1; $i <= 5; $i++) {
            $adds[] = [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => $i,
                'address_id' => $i
            ];
        }

        for($i=5; $i <= 9; $i++) {
            $adds[] = [
                'start_date' => '2024-01-01',
                'end_date' => null,
                'user_id' => $i,
                'address_id' => $i + 1
            ];
        }

        DB::table('customer_addresses')->insert($adds);
    }
}
