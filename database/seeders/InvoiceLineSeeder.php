<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        $invoiceLines = [];

        for ($i = 1; $i <= 11; $i++) {
            // Product line
            $invoiceLines[] = [
                'type' => 'Product',
                'unitPrice' => 0.25,
                'amount' => 180,
                'consumptionID' => null,
                'invoiceID' => $i,
            ];

            // Basic service fee line
            $invoiceLines[] = [
                'type' => 'Basic Service Fee',
                'unitPrice' => 10,
                'amount' => 10,
                'consumptionID' => null,
                'invoiceID' => $i,
            ];

            // Distribution fee line
            $invoiceLines[] = [
                'type' => 'Distribution Fee',
                'unitPrice' => 10,
                'amount' => 10,
                'consumptionID' => null,
                'invoiceID' => $i,
            ];
        }

        DB::table('invoiceLine')->insert($invoiceLines);
    }
}