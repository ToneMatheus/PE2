<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee_contract;

class EmployeeContractSeeder extends Seeder
{
    public function run(): void
    {
        $contracts = [];

        for($i = 1; $i <= 15; $i++){
            $contracts[] = [
                'employee_profile_id' => $i,
                'start_date' => '2024-01-01',
                'end_date' => '2025-02-14',
                'type' => 'Contract',
                'status' => 'Active',
                'salary_per_month' => 4000.00,
            ];
        }

        DB::table('employee_contracts')->insert($contracts);

    }

}
