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
        DB::table('employee_contracts')->insert([
            [
                'employee_profile_id' => 1,
                'start_date' => '2024-01-01',
                'end_date' => '2025-02-14',
                'type' => 'Contract',
                'status' => 'Active',
                'salary_per_month' => 4000.00,
            ],
            [
                'employee_profile_id' => 2,
                'start_date' => '2023-02-15',
                'end_date' => null,
                'type' => 'Full-time',
                'status' => 'Active',
                'salary_per_month' => 5000.00,
            ],
            [
                'employee_profile_id' => 3,
                'start_date' => '2023-02-15',
                'end_date' => null,
                'type' => 'Full-time',
                'status' => 'Active',
                'salary_per_month' => 3500.00,
            ],
        ]);
    }
}
