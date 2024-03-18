<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee_profile;

class EmployeeProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employee_profiles')->insert([
            [
                'id' => 1,
                'hire_date' => '2024-01-01',
                'department' => 'Finance',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [
                'id' => 2,
                'hire_date' => '2024-01-01',
                'department' => null,
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [
                'id' => 3,
                'hire_date' => '2024-01-01',
                'department' => 'Customer service',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
        ]);
    }
}
