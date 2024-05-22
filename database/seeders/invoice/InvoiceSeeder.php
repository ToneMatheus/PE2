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
        $prices = [113.75, 455.4, 108.75, 99.8];

        for($i = 1; $i < 5; $i++){ //Annual
            $ccID = ($i == 4) ? 3 : $i;
            DB::table('invoices')->insert([
                'invoice_date' => '2024-01-15',
                'due_date' => '2024-01-30',
                'total_amount' => 30,
                'status' => 'paid',
                'customer_contract_id' => $ccID,
                'meter_id' => $i,
                'type' => 'Annual'
            ]);
        }

        for($i = 1; $i < 5; $i++){
            for($month = 2; $month <= 12; $month++){
                $invoiceDate = Carbon::createFromDate(2024, $month, 15);
                $dueDate = $invoiceDate->copy()->endOfMonth();

                $ccID = ($i == 4) ? 3 : $i;

                DB::table('invoices')->insert([
                    'invoice_date' => $invoiceDate->toDateString(),
                    'due_date' => $dueDate->toDateString(),
                    'total_amount' => $prices[$i-1],
                    'status' => 'paid',
                    'customer_contract_id' => $ccID,
                    'meter_id' => $i,
                    'type' => 'Monthly'
                ]);
            }
        }

        DB::table('invoices')->insert([
            'invoice_date' => '2025-01-08',
            'due_date' => '2025-01-22',
            'total_amount' => '200',
            'status' => 'sent',
            'customer_contract_id' => 5,
            'meter_id' => 6,
            'type' => 'Monthly'
        ]);

        DB::table('invoices')->insert([
            'invoice_date' => '2024-12-18',
            'due_date' => '2025-01-01',
            'total_amount' => '300',
            'status' => 'sent',
            'customer_contract_id' => 6,
            'meter_id' => 7,
            'type' => 'Monthly'
        ]);
    }
}