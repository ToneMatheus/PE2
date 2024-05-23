<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Balance;

class BalanceSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 1; $i <= 9; $i++){
            DB::table('balances')->insert([
                [
                    'employee_profile_id' => $i,
                    'holiday_type_id' => 1,
                    'yearly_holiday_credit' => 20,
                    'used_holiday_credit' => 0,
                    'start_date' => '2024-01-01'
                ],
            ]);
        }
        DB::table('balances')->insert([
            [
                'employee_profile_id' => 2,
                'holiday_type_id' => 2,
                'yearly_holiday_credit' => 10,
                'used_holiday_credit' => 0,
                'start_date' => '2024-01-01'
            ],
        ]);
    }
}
