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
            ],
            [
                'id' => 2,
                'hire_date' => '2024-01-01',
            ],
            [
                'id' => 3,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 4,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 5,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 6,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 7,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 8,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 9,
                'hire_date' => '2024-01-01',
            ],
        ]);
    }
}
