<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payslips; // Import your model
use Carbon\Carbon;

class InsertDataCommand extends Command
{
    protected $signature = 'insert:data';

    protected $description = 'Insert data into the database table';

    function generateBelgianIBAN() {
        $bankCode = '0' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT); // Random bank code (4 digits)
        $accountNumber = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT); // Random account number (10 digits)
        $countryCode = 'BE'; // Country code for Belgium
        $checkDigits = 98 - bcmod($bankCode . $accountNumber . 'BE00', '97'); // Calculate check digits
    
        return 'BE' . str_pad($checkDigits, 2, '0', STR_PAD_LEFT) . $bankCode . $accountNumber;
    }    

    public function handle()
    {
        // Generate sample data (you can replace this with your own data generation logic)
        $data = [];
        
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $now = Carbon::now();
        $belgianIBAN = generateBelgianIBAN();
        
        for($i = 1; $i <= 9; $i++){
            $data[] = [
                'employee_profile_id' => $i,
                'startDate' => $startOfMonth,
                'endDate' => $endOfMonth,
                'creationDate' => $now,
                'nrDaysWorked' => 20,
                'totalHours' => 103,
                'IBAN' => $belgianIBAN,
                'amountPerHour' => '15.9'
            ];
        }

        // Insert data into the database table
        foreach ($data as $entry) {
            Payslips::create($entry);
        }
    }
}