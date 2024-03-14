<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice_line;

class InvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 1; $i<= 4; $i++){
            $ccID = ($i == 4) ? 3 : $i;
            $invoices = DB::table('invoices')->where('customer_contract_id', '=', $ccID)->get();

            $consumptions = DB::table('consumptions')
            ->select('consumptions.id')
            ->join('index_values as iv', 'consumptions.prev_index_id', '=', 'iv.id')
            ->where('iv.meter_id', $i)
            ->get();

            $consumptionIds = [];

            foreach ($consumptions as $consumption) {
                $consumptionIds[] = $consumption->id;
            }
            
            $count = 0;
            foreach($invoices as $invoice){
                DB::table('invoice_lines')->insert([
                    'type' => 'Electricity',
                    'unit_price' => 0.28,
                    'amount' => 300,
                    'consumption_id' => $consumptionIds[$count],
                    'invoice_id' => $invoice->id,
                ]);
    
                DB::table('invoice_lines')->insert([
                    'type' => 'Basic Service Fee',
                    'unit_price' => 10,
                    'amount' => 10,
                    'consumption_id' => null,
                    'invoice_id' => $invoice->id,
                ]);

                DB::table('invoice_lines')->insert([
                    'type' => 'Distribution Fee',
                    'unit_price' => 10,
                    'amount' => 10,
                    'consumption_id' => null,
                    'invoice_id' => $invoice->id,
                ]);
            }
            $count++;
        }
    }
}