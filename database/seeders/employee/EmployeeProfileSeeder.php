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
        Employee_profile::insert([
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
            [   
                'id' => 4,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('joseph')
            ],
            [   
                'id' => 5,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('jimmy')
            ],
            [   
                'id' => 6,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('james')
            ],
            [   
                'id' => 7,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('jane')
            ],
            [   
                'id' => 8,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('pearl')
            ],
            [   
                'id' => 9,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com',
                'password' => Hash::make('rose')
            ],
        ]);
    }
}
