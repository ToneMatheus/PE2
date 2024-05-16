<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryRangesSeeder extends Seeder
{
    public function run()
    {
        $roles = DB::select("SELECT * FROM roles");

        foreach ($roles as $role) {
            if ($role->role_name == 'Manager') {
                DB::table('salary_ranges')->insert([
                    [
                        'min_salary' => 5000,
                        'max_salary' => 7000,
                        'role_id' => $role->id
                    ],
                ]);
            }
            elseif ($role->role_name == 'Employee') {
                DB::table('salary_ranges')->insert([
                    [
                        'min_salary' => 4000,
                        'max_salary' => 5000,
                        'role_id' => $role->id
                    ],
                ]);
            }
        }
    }
}
