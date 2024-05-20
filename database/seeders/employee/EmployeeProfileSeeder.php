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
                'job' => null,
                'notes' => null,
                'line_number' => null,
            ],
            [
                'id' => 2,
                'hire_date' => '2024-01-01',
                'job' => null,
                'notes' => null,
                'line_number' => null,
            ],
            [
                'id' => 3,
                'hire_date' => '2024-01-01',
                'job' => null,
                'notes' => null,
                'line_number' => null,
            ],
            [
                'id' => 4,
                'job' => 'Software Developer',
                'hire_date' => '2024-01-01',
                'notes' => 'Experienced in full stack development',
                'line_number' => 1,
            ],
            [
                'id' => 5,
                'hire_date' => '2024-01-01',
                'job' => null,
                'notes' => null,
                'line_number' => null,
            ],
            [
                'id' => 6,
                'hire_date' => '2024-01-01',
                'job' => null,
                'notes' => null,
                'line_number' => null,
            ],
            [
                'id' => 1000,
                'hire_date' => '1970-01-01',
                'job' => null,
                'notes' => null,
                'line_number' => null,
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
            [   
                'id' => 10,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 11,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 12,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 13,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 14,
                'hire_date' => '2024-01-01',
            ],
            [   
                'id' => 15,
                'hire_date' => '2024-01-01',
            ],
        ]);
    }
}
