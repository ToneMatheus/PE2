<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PayslipSeeder extends Seeder
{
    public function run()
    {

        DB::table('payslips')->insert([
            'startDate' => Carbon::now()->subWeeks(3)->toDateString(),//three weeks ago
            'endDate' => Carbon::now()->addDays(4)->toDateString(),//in one 4 days time
            'creationDate' => Carbon::now()->subDays(2)->toDateString(),
            'nrDaysWorked' => 15,
            'totalHours' => 90.0,
            'IBAN' => 'BE71 0961 2345 6769',
            'amountPerHour' => 15.6,
            'employeeID' => 1,//this should be replaced with the actual employee ID!
        ]);
    }
}
