<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoice = [
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
            ];

            foreach ($invoice as $invoiceData){
                Invoice::create($invoiceData);
            }
    }
}