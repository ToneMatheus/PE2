<?php

namespace Database\Seeders\Customer;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer_contract;
use Carbon\Carbon;

class CustomerContractSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now()->toDateString();
        $twoWeekCooldown = Carbon::now()->addWeeks(2)->toDateString();
        $newContractBegin = Carbon::now()->addWeeks(2)->addDay()->toDateString();
        $newContractBeginGap = Carbon::now()->addWeeks(3)->toDateString();

        $testContractBegin = Carbon::now()->addDays(1)->toDateString();
        $testContractBeginGap = Carbon::now()->addDays(14)->toDateString();

        for($i=1; $i <= 9; $i++){
            DB::table('customer_contracts')->insert([
                'id' => $i,
                'user_id' => $i + 2,
                'start_date' => '2022-01-01',
                'end_date' => null,
                'type' => 'Standard',
                'price' => 1000,
                'status' => 'Active'
            ]);
        }

        DB::table('customer_contracts')->insert([
            [
                'id' => 10,
                'user_id' => 12,
                'start_date' => '2022-01-01',
                'end_date' => $twoWeekCooldown,
                'type' => 'Standard',
                'price' => 1000,
                'status' => 'Active'
            ]
        ]);

        for($i=11; $i <= 16; $i++){
            DB::table('customer_contracts')->insert([
                'id' => $i,
                'user_id' => $i + 2,
                'start_date' => '2022-01-01',
                'end_date' => null,
                'type' => 'Standard',
                'price' => 1000,
                'status' => 'Active'
            ]);
        }

        DB::table('customer_contracts')->insert([
            [
                'id' => 17,
                'user_id' => 19,
                'start_date' => $newContractBegin,
                'end_date' => null,
                'type' => 'Standard',
                'price' => 1000,
                'status' => 'Active'
            ]
        ]);
    }
}
