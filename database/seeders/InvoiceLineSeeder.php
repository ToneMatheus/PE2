<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoiceLine')->insert([
            [
                'ID' => 1,
                'type' => 'Product',
                'quantity' => 2,
                'unitPrice' => 10.50,
                'amount' => 21.00,
                'consumptionID' => null,
                'invoiceID' => 1,
            ],
            [
                'ID' => 2,
                'type' => 'Service',
                'quantity' => 1,
                'unitPrice' => 25.00,
                'amount' => 25.00,
                'consumptionID' => null,
                'invoiceID' => 1,
            ],
            [
                'ID' => 3,
                'type' => 'Product',
                'quantity' => 3,
                'unitPrice' => 15.75,
                'amount' => 47.25,
                'consumptionID' => null,
                'invoiceID' => 2,
            ],
            [
                'ID' => 4,
                'type' => 'Service',
                'quantity' => 2,
                'unitPrice' => 30.00,
                'amount' => 60.00,
                'consumptionID' => null,
                'invoiceID' => 2,
            ]
            ]);
    }
}