<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Payslip;

class PayslipSeeder extends Seeder
{
    public function run(): void
    {
        $payslips = [];

        for($i = 1; $i <= 15; $i++){
            $payslips[] = [
                'employee_profile_id' => $i,
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'creation_date' => '2024-01-15',
                'nbr_days_worked' => 20, 
                'total_hours' => 160, 
                'IBAN' => 'Your IBAN number',
                'amount_per_hour' => 25.00,
            ];
        }

        DB::table('payslips')->insert($payslips);
    }
}
