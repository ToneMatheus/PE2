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

        for ($i = 1; $i <= 15; $i++) {
            // $roleid = DB::select("select role_id from user_roles inner join users on user_roles.user_id = users.id where employee_profile_id = $i");
            // $id = $roleid[0]->role_id;
            $user_id = DB::select("select * from users where employee_profile_id = $i");
            $uid = $user_id[0]->id;
            $role_id = DB::select("select * from user_roles where user_id = $uid");
            $id = $role_id[0]->role_id;

            if ($id === 1 || $id === 2) {
                $salary_range_id_result = DB::select("SELECT id FROM salary_ranges WHERE role_id = $id");
                $benefits_id_result = DB::select("SELECT id FROM employee_benefits WHERE role_id = $id");
            
                if (!empty($salary_range_id_result) && !empty($benefits_id_result)) {
                    $contracts[] = [
                        'employee_profile_id' => $i,
                        'start_date' => '2024-01-01',
                        'end_date' => '2025-02-14',
                        'type' => 'Contract',
                        'status' => 'Active',
                        'role_id' => $id,
                        'salary_range_id' => $salary_range_id_result[0]->id,
                        'benefits_id' => $benefits_id_result[0]->id, 
                        'salary_per_month' => 0
                    ];
                }
            }
        }

        DB::table('employee_contracts')->insert($contracts);
    }

}
