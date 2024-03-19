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
        DB::table('balances')->insert([
            [
                'employee_profile_id' => 1,
                'holiday_type_id' => 1,
                'yearly_holiday_credit' => 20,
                'used_holiday_credit' => 0,
                'start_date' => '2024-01-01'
            ],
            [
                'employee_profile_id' => 2,
                'holiday_type_id' => 2,
                'yearly_holiday_credit' => 25,
                'used_holiday_credit' => 5,
                'start_date' => '2024-01-01'
            ],
        ]);
    }
}
