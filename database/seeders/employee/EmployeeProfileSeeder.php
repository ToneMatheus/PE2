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
                'work_email' => 'bd1@example.com'
            ],
            [
                'id' => 2,
                'hire_date' => '2024-01-01',
                'work_email' => 'jd2@example.com',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [
                'id' => 3,
                'hire_date' => '2024-01-01',
                'work_email' => 'kd3@example.com'
            ],
            [   
                'id' => 4,
                'hire_date' => '2024-01-01',
                'department' => 'Maintenace & IT',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [   
                'id' => 5,
                'hire_date' => '2024-01-01',
                'department' => 'Maintenace & IT',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [   
                'id' => 6,
                'hire_date' => '2024-01-01',
                'department' => 'Customer service',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [   
                'id' => 7,
                'hire_date' => '2024-01-01',
                'department' => 'Finance',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
            [   
                'id' => 8,
                'hire_date' => '2024-01-01',
                'department' => 'Finance',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
            [   
                'id' => 9,
                'hire_date' => '2024-01-01',
                'department' => 'Sales and marketing',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
            [   
                'id' => 4,
                'hire_date' => '2024-01-01',
                'department' => 'Maintenace & IT',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [   
                'id' => 5,
                'hire_date' => '2024-01-01',
                'department' => 'Maintenace & IT',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [   
                'id' => 6,
                'hire_date' => '2024-01-01',
                'department' => 'Customer service',
                'nationality' => 'Belgian',
                'sex' => 'Male'
            ],
            [   
                'id' => 7,
                'hire_date' => '2024-01-01',
                'department' => 'Finance',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
            [   
                'id' => 8,
                'hire_date' => '2024-01-01',
                'department' => 'Finance',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
            [   
                'id' => 9,
                'hire_date' => '2024-01-01',
                'department' => 'Sales and marketing',
                'nationality' => 'Belgian',
                'sex' => 'Female'
            ],
        ]);
    }
}
