<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee_profile;
use Illuminate\Support\Facades\Hash;

class EmployeeProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employee_profiles')->insert([
            [
                'id' => 1,
                'hire_date' => '2024-01-01',
                'work_email' => 'bd1@example.com',
                'password' => Hash::make('bob')
            ],
            [
                'id' => 2,
                'hire_date' => '2024-01-01',
                'work_email' => 'jd2@example.com',
                'password' => Hash::make('jef')
            ],
            [
                'id' => 3,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('kim')
            ],
        ]);
    }
}
