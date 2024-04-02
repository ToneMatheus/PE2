<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('invoices')->insert([
            [
                'id' => 1,
                'invoice_date' => '2024-01-15',
                'due_date' => '2024-01-30',
                'total_amount' => 113.75,
                'status' => 'paid',
                'customer_contract_id' => 1,
                'type' => 'Monthly'
            ],
            [
                'id' => 2,
                'invoice_date' => '2024-02-15',
                'due_date' => '2024-02-29',
                'total_amount' => 113.75,
                'status' => 'paid',
                'customer_contract_id' => 1,
                'type' => 'Monthly'
            ]
        ]);

        DB::table('invoices')->insert([
            [
                'id' => 3,
                'invoice_date' => '2024-01-15',
                'due_date' => '2024-01-30',
                'total_amount' => 455.4,
                'status' => 'paid',
                'customer_contract_id' => 2,
                'type' => 'Monthly'
            ],
            [
                'id' => 4,
                'invoice_date' => '2024-02-15',
                'due_date' => '2024-02-29',
                'total_amount' => 455.4,
                'status' => 'paid',
                'customer_contract_id' => 2,
                'type' => 'Monthly'
            ],
            [
                'id' => 5,
                'invoice_date' => '2024-03-15',
                'due_date' => '2024-03-29',
                'total_amount' => 455.4,
                'status' => 'sent',
                'customer_contract_id' => 2,
                'type' => 'Monthly'
            ]
        ]);

        DB::table('invoices')->insert([
            [
                'id' => 6,
                'invoice_date' => '2024-01-15',
                'due_date' => '2024-01-30',
                'total_amount' => 108.75,
                'status' => 'paid',
                'customer_contract_id' => 3,
                'type' => 'Monthly'
            ],
            [
                'id' => 7,
                'invoice_date' => '2024-02-15',
                'due_date' => '2024-02-29',
                'total_amount' => 108.75,
                'status' => 'paid',
                'customer_contract_id' => 3,
                'type' => 'Monthly'
            ]
        ]);

        DB::table('invoices')->insert([
            [
                'id' => 8,
                'invoice_date' => '2024-01-15',
                'due_date' => '2024-01-30',
                'total_amount' => 99.8,
                'status' => 'paid',
                'customer_contract_id' => 3,
                'type' => 'Monthly'
            ],
            [
                'id' => 9,
                'invoice_date' => '2024-02-15',
                'due_date' => '2024-02-29',
                'total_amount' => 99.8,
                'status' => 'paid',
                'customer_contract_id' => 3,
                'type' => 'Monthly'
            ]
        ]); 
    }
}