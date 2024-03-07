<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [];

        for($i = 1; $i <= 11; $i++){
            $invoiceDate = Carbon::parse('2024-01-01')->addMonths($i - 1);
            $dueDate = $invoiceDate->copy()->addWeeks(2);
            $totalAmount = 200;

            $invoices[] = [
                'ID' => $i,
                'invoiceDate' => $invoiceDate,
                'dueDate' => $dueDate,
                'totalAmount' => $totalAmount,
                'status' => 'Paid',
                'customerContractID' => 1,
                'type' => 'Electrical'
            ];
        }

        $invoices[] = [
            'ID' => 12,
            'invoiceDate' => '2024-12-01',
            'dueDate' => '2024-12-15',
            'totalAmount' => 20,
            'status' => 'Paid',
            'customerContractID' => 1,
            'type' => 'Electrical'
        ];

        DB::table('invoice')->insert($invoices);
    }
}