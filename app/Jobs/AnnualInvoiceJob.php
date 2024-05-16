<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\{
    User, 
    Invoice,
    Contract_product,
    Product,
    Invoice_line,
    CreditNote,
    Estimation,
    Index_Value,
    Discount,
    Meter
};

use App\Mail\AnnualInvoiceMail;
use App\Http\Controllers\EstimationController;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\cronJobTrait;
use App\Services\StructuredCommunicationService;
use App\Services\InvoiceFineService;


class AnnualInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    protected $now;
    protected $year;
    protected $month;
    protected $mID;

    public function __construct($mID)
    {
        $this->now = config('app.now');
        $this->month = $this->now->format('m');
        $this->year = $this->now->format('Y');
        $this->mID = $mID;
    }

    public function handle()
    {
        $this->jobStart();

        $now = $this->now;
        $month = $this->month;
        $year = $this->year;

        $customer = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
        ->where('m.type', '=', 'Electricity')
        ->where('m.status', '=', 'Installed')
        ->where('m.is_smart', '=', '0')
        ->where('m.id', '=', $this->mID)
        ->where('m.expecting_reading', '=', 1)
        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
        ->first();

        if(!is_null($customer)){
            $startContract = Carbon::parse($customer->startContract);

            $invoiceDate = $startContract->addYear()->addWeeks(2);
            $lastInvoiceDate = $invoiceDate->copy()->setYear($year-1);
            $nextInvoiceDate = $invoiceDate->copy()->addYear();

            $invoiceDate->setYear($year);
            $invoiceDate->setMonth($month);
            $invoiceDate->setTimezone('Europe/Berlin');

            $consumption = User::join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
            ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
            ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.Address_id')
            ->join('Meters', 'Meter_addresses.Meter_id', '=', 'Meters.id')
            ->join('Index_values', 'Meters.id', '=', 'Index_values.meter_id')
            ->join('Consumptions', 'Index_values.id', '=', 'Consumptions.Current_index_id')
            ->where('reading_date', '>=', $lastInvoiceDate)
            ->where('reading_date', '<=', $now)
            ->where('meters.id', '=', $customer->mID)
            ->select('Consumptions.*')
            ->first();

            $meterReadings = Index_Value::where('meter_id', $customer->mID)
            ->where('reading_date', '>=', $lastInvoiceDate)
            ->where('reading_date', '<', $nextInvoiceDate)
            ->first();

            $estimationResult = Estimation::where('meter_id', '=', $customer->mID)
            ->first();

            $estimation = $estimationResult->estimation_total;

            $contractProduct = Contract_product::join('products as p', 'p.id', '=', 'contract_products.product_id')
            ->where('customer_contract_id', '=', $customer->ccID)
            ->where('meter_id', '=', $customer->mID)
            ->whereNull('contract_products.end_date')
            ->select('contract_products.id as cpID', 'contract_products.start_date as cpStartDate', 'p.product_name as productName',
            'p.id as pID')
            ->first();

            $discounts = Discount::where('discounts.contract_product_id', '=', $contractProduct->cpID)
            ->whereDate('discounts.end_date', '>=', $now->format('Y/m/d'))
            ->get();

            $productTariff = Product::join('product_tariffs as pt', 'pt.product_id', '=', 'products.id')
            ->join('tariffs as t', 't.id', '=', 'pt.id')
            ->where('products.id', '=', $contractProduct->pID)
            ->whereNull('pt.end_date')
            ->first();

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
    
                    $monthlyExtraAmount = ($consumption->consumption_value) * $productTariff->rate;
    
                    if ($discountRate > 0) {
                        $monthlyExtraAmount -= ($monthlyExtraAmount * $discountRate);
                    }
                
                    $extraAmount += $monthlyExtraAmount;
                }
            } else {
                $extraAmount = ($consumption->consumption_value) ? $consumption->consumption_value * $productTariff->rate : 0;
            }

            $invoiceRunJob = new InvoiceRunJob();
            $monthlyInvoices = $invoiceRunJob->getMonthlyInvoices($customer->ccID, $customer->mID);

            if($extraAmount > 0){                   //Invoice
                $invoiceData = [
                    'invoice_date' => $now->format('Y-m-d'),
                    'due_date' => $now->addWeeks(2)->format('Y-m-d'),
                    'total_amount' => $extraAmount,
                    'status' => 'sent',
                    'customer_contract_id' => $customer->ccID,
                    'meter_id' => $customer->mID,
                    'type' => 'Annual'
                ];

            } else{                              //Credit note
                $invoiceData = [
                    'invoice_date' => $now->format('Y-m-d'),
                    'due_date' => $now->addWeeks(2)->format('Y-m-d'),
                    'total_amount' => $extraAmount,
                    'status' => 'paid',
                    'customer_contract_id' => $customer->ccID,
                    'meter_id' => $customer->mID,
                    'type' => 'Annual'
                ];
            }

            $invoice = Invoice::create($invoiceData);
            $lastInserted = $invoice->id;

            $scService = new StructuredCommunicationService;
            $strCom = $scService->generate($lastInserted);
            $scService->addStructuredCommunication($strCom, $lastInserted);

            if ($extraAmount <= 0) {
                CreditNote::create([
                    'invoice_id' => $lastInserted,
                    'type' => 'credit note',
                    'amount' => $extraAmount,
                    'user_id' => $customer->uID,
                    'status' => 1
                ]);
            }

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $productTariff->rate,
                'amount' => $extraAmount,
                'consumption_id' => $consumption->id,
                'invoice_id' => $lastInserted
            ]);

            $fineService = new InvoiceFineService;
            $fineService->unpaidInvoiceFine($lastInserted);
            
            $newInvoiceLine = Invoice_line::where('invoice_id', '=', $lastInserted)->first();
           
            $invoiceRunJob->sendAnnualMail($invoice, $customer, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices);
            EstimationController::UpdateEstimation($customer->mID);  

            Meter::where('id', '=', $customer->mID)
            ->update([
                'expecting_reading' => 0
            ]);

            $this->jobCompletion("Finished annual invoice");
        }
    }
}
