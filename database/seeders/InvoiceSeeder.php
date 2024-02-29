<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoice')->insert([
            [
                'ID' => 1,
                'invoiceDate' => '2024-01-01',
                'dueDate' => '2024-02-15',
                'totalAmount' => 200.00,
                'status' => 'Paid',
                'customerContractID' => 1
            ],
            [
                'ID' => 2,
                'invoiceDate' => '2024-01-01',
                'dueDate' => '2024-02-15',
                'totalAmount' => 200.00,
                'status' => 'Unpaid',
                'customerContractID' => 2
            ]
            ]);
    }
}