<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeBenefitsSeeder extends Seeder
{
    public function run()
    {
        $roles = DB::select("SELECT * FROM roles");
        
        foreach ($roles as $role) {
            DB::table('employee_benefits')->insert([
                [
                    'benefit_name' => 'Health Insurance',
                    'description' => 'Comprehensive health coverage for employees.',
                    'role_id' => $role->id
                ],
                [
                    'benefit_name' => 'Employee Discounts',
                    'description' => 'We provide discounts on company products or services, or partner discounts with external vendors.',
                    'role_id' => $role->id
                ],
                [
                    'benefit_name' => 'Sick Leave',
                    'description' => 'We offer paid sick leave for employees to use when they\'re ill or need to care for sick family members.',
                    'role_id' => $role->id
                ],
            ]);

            if ($role->role_name == 'Manager') {
                DB::table('employee_benefits')->insert([
                    [
                        'benefit_name' => 'Company Car',
                        'description' => 'The company provides a car for managers',
                        'role_id' => $role->id,
                    ],
                    [
                        'benefit_name' => 'Recognition Programs',
                        'description' => 'Special recognition programs to acknowledge the contributions and achievements of managers in driving organizational success.',
                        'role_id' => $role->id,
                    ],
                    [
                        'benefit_name' => 'Additional Paid Time Off',
                        'description' => 'Managers might receive extra vacation days or personal leave to recharge and manage their workload effectively.',
                        'role_id' => $role->id,
                    ],
                ]);
            }
            elseif ($role->role_name == 'Employee') {
                DB::table('employee_benefits')->insert([
                    [
                        'benefit_name' => 'Wellness programs',
                        'description' => 'We provide access to programs that promote physical and mental well-being, such as gym memberships, wellness workshops, and mental health resources.',
                        'role_id' => $role->id
                    ],
                    [
                        'benefit_name' => 'Training and Development Programs:',
                        'description' => 'We offer opportunities for employees to enhance their skills and advance their careers through training workshops, conferences, or tuition reimbursement programs.',
                        'role_id' => $role->id
                    ],
                ]);
            }
        }
    }
}
