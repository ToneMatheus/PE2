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
        $count = 1;

        for($i = 1; $i <= 4; $i++){
            $consumptions = DB::table('consumptions')
            ->join('index_values as iv', 'consumptions.prev_index_id', '=', 'iv.id')
            ->where('iv.meter_id', $i)
            ->get();

            $ccID = ($i == 4) ? 3 : $i;
            $tariffQuery = DB::table('customer_contracts as cc')
            ->join('contract_products as cp', 'cp.customer_contract_id', '=', 'cc.id')
            ->join('products as p', 'p.id', '=', 'cp.product_id')
            ->join('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
            ->join('tariffs as t', 't.id', '=', 'pt.tariff_id')
            ->first();

            $tariff = $tariffQuery->rate;

            foreach($consumptions as $consumption){
                if($consumption->consumption_value <= 0){
                    $estimation = DB::table('estimations')->where('meter_id', '=', $i)->first();

                    $estimationTotal = $estimation->estimation_total;
                    $totalAmount = ($estimationTotal * $tariff) + 10 + 10;
                } else {
                    $consumptionTotal = $consumption->consumption_value;
                    $totalAmount = ($consumptionTotal * $tariff) + 10 + 10;
                }

                $endDate = Carbon::parse($consumption->end_date);

                DB::table('invoices')->insert([
                    'id' => $count,
                    'invoice_date' => $consumption->end_date,
                    'due_date' => $endDate->copy()->addDays(15),
                    'total_amount' => $totalAmount,
                    'status' => 'paid',
                    'customer_contract_id' => $ccID,
                    'meter_id' => $i,
                    'type' => 'Monthly'
                ]);
                
                $count++;
            }
        }
    }
}