<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Carbon;
use App\Models\{
    Consumption,
    Invoice,
    Contract_product,
    Invoice_line,
    CreditNote,
    Estimation,
    Discount,
    Meter
};
use App\Services\InvoiceFineService;
use App\Http\Controllers\EstimationController;
use Illuminate\Support\Facades\Log;

//not finished yet: mail and logging still needed
//this job calculates data for the final settlement invoice and stores it in database
//the job only handles the final settlement invoice of 1 leaving customer
//how do we tell the job which meter?
class FinalSettlementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $now;
    protected $year;
    protected $month;

    protected $meterID;
    protected $ccID;
    protected $userID;

    public function __construct($meterID)
    {
        $this->now = config('app.now');
        $this->month = $this->now->format('m');
        $this->year = $this->now->format('Y');
        $this->meterID = $meterID;
    }

    public function handle()
    {
        $meterID = $this->meterID;

        try
        {
            $meter = Meter::findOrFail($meterID);
        }
        catch (\Exception $e)
        {
            Log::error("Unable to find meter record of with meterID " . $meterID);
        }
        
        try
        {
            //query assumes the customer contract for this meter is still active
            $result = Meter::select('customer_contracts.id', 'customer_contracts.user_id')
                ->leftJoin('contract_products as cp', 'meters.id', '=', 'cp.meter_id')
                ->leftJoin('customer_contracts as cc', 'cp.customer_contract_id', '=', 'cc.id')
                ->where('meters.id', $meterID)
                ->whereNull('cp.end_date')
                ->first();

            $this->ccID = $result->id;
            $this->userID = $result->user_id;
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            // no result found
            Log::error("Unable to retrieve customerContractID and userID");
        }

        // 1) check if we have meter reading
        //      if not, reminder system
        if ($meter->expecting_reading) //assuming expecting_reading is set before invoice run
        {
            //reminder system or other actions
        }
        else 
        {
            // 2) calculate amount based on actual consumption
            $this->calculateInvoice($meter);
        }
    }

    public function calculateInvoice($meter)
    {
        try
        {
            //consumption
            //get most recent consumtion
            $consumption = Consumption::rightJoin('index_values as iv', 'iv.id', '=', 'consumptions.current_index_id')
                ->where('iv.meter_id', $meter->id)
                ->orderByDesc('iv.reading_date')
                ->limit(1)
                ->first();

            //get estimation for this meter
            $estimation = Estimation::where('meter_id', $meter->id)->value('estimation_total');

            //discounts
            $discounts = Discount::rightJoin('contract_products as cp', 'cp.id', '=', 'discounts.contract_product_id')
                ->where('cp.meter_id', '=', $meter->id)
                ->whereDate('discounts.end_date', '>=', $this->now->format('Y/m/d'))
                ->get();

            //get tariff rate
            $tariffRate = Contract_product::leftJoin('products as p', 'cp.product_id', '=', 'p.id')
                ->leftJoin('product_tariffs as pt', 'p.id', '=', 'pt.product_id')
                ->leftJoin('tariffs as t', 'pt.tariff_id', '=', 't.id')
                ->whereNull('pt.end_date')
                ->where('cp.customer_contract_id', $this->ccID)
                ->where('cp.meter_id', $meter->id)
                ->orderByDesc('cp.start_date')
                ->value('t.rate');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            Log::error("Unable to find information for meter " . $meter->id . " in database.");
        }

        //calculate discounts
        $extraAmount = 0;

        if(!is_null($discounts)){
            for ($i = 1; $i <= 12; $i++) {
                $discountRate = 0;
            
                foreach ($discounts as $discount) {
                    $startMonth = (new Carbon($discount->start_date))->format('m');
                    $endMonth = (new Carbon($discount->end_date))->format('m');
            
                    if ($i >= $startMonth && $i <= $endMonth) {
                        $discountRate = $discount->rate;
                        break;
                    }
                }

                $monthlyExtraAmount = ($consumption->consumption_value) * $tariffRate;

                if ($discountRate > 0) {
                    $monthlyExtraAmount -= ($monthlyExtraAmount * $discountRate);
                }
            
                $extraAmount += $monthlyExtraAmount;
            }
        } else {
            $extraAmount = ($consumption->consumption_value) ? $consumption->consumption_value * $tariffRate : 0;
        }

        //calculate
        $monthlyInvoices = $this->getMonthlyInvoices();

        if($extraAmount > 0){                   //Invoice
            $invoiceData = [
                'invoice_date' => $this->now->format('Y-m-d'),
                'due_date' => $this->now->addWeeks(2)->format('Y-m-d'),
                'total_amount' => $extraAmount,
                'status' => 'sent',
                'customer_contract_id' => $this->ccID,
                'meter_id' => $meter->id,
                'type' => 'Annual'
            ];

        } else{                              //Credit note
            $invoiceData = [
                'invoice_date' => $this->now->format('Y-m-d'),
                'due_date' => $this->now->addWeeks(2)->format('Y-m-d'),
                'total_amount' => $extraAmount,
                'status' => 'paid',
                'customer_contract_id' => $this->ccID,
                'meter_id' => $meter->id,
                'type' => 'Annual'
            ];
        }

        // 3) store in database
        try
        {
            $invoice = Invoice::create($invoiceData);
            $lastInserted = $invoice->id;

            if ($extraAmount <= 0) {
                CreditNote::create([
                    'invoice_id' => $lastInserted,
                    'type' => 'credit note',
                    'amount' => $extraAmount,
                    'user_id' => $this->userID,
                    'status' => 1
                ]);
            }

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $tariffRate,
                'amount' => $extraAmount,
                'consumption_id' => $consumption->id,
                'invoice_id' => $lastInserted
            ]);

            $fineService = new InvoiceFineService;
            $fineService->unpaidInvoiceFine($lastInserted);
        }
        catch (\Exception $e)
        {
            Log::error("Unable to store final settlement invoice for meter " . $meter->id . " in database.");
        }
        
        $newInvoiceLine = Invoice_line::where('invoice_id', '=', $lastInserted)->first();
        EstimationController::UpdateEstimation($meter->id);  
        // 4) send mail (not yet added)
    }

    public function getMonthlyInvoices() //(function copied from InvoiceRunJob)
    {
        $currentYear = $this->year;

        // Query monthly invoices and their lines for the given customer and the current year
        $monthlyInvoices = Invoice::join('customer_contracts as cc', 'invoices.customer_contract_id', '=', 'cc.id')
            ->join('users as u', 'cc.user_id', '=', 'u.id')
            ->join('invoice_lines as il', 'invoices.id', '=', 'il.invoice_id')
            ->where('cc.id', $this->ccID)
            ->where('invoices.meter_id', '=', $this->meterID)
            ->where('invoices.type', 'Monthly')
            ->whereYear('invoices.invoice_date', $currentYear)
            ->orderBy('invoices.invoice_date')
            ->select('invoices.*', 'il.*')
            ->get();

        // Organize the data by grouping lines by invoice (month)
        $monthlyInvoicesData = [];
        foreach ($monthlyInvoices as $monthlyInvoice) {
            $month = Carbon::createFromFormat('Y-m-d', $monthlyInvoice->invoice_date)->format('F');
            $monthlyInvoicesData[$month][] = $monthlyInvoice;
        }

        return $monthlyInvoicesData;
    }
}
