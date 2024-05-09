<?php

namespace App\Jobs;

use App\Http\Controllers\EstimationController;
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
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

use App\Mail\AnnualInvoiceMail;
use App\Mail\MonthlyInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\cronJobTrait;

use App\Services\InvoiceFineService;
use App\Services\StructuredCommunicationService;

class InvoiceRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    protected $domain = "http://127.0.0.1:8000"; //change later
    protected $now;
    protected $year;
    protected $month;

    public function __construct()
    {
        $this->now = config('app.now');
        $this->month = $this->now->format('m');
        $this->year = $this->now->format('Y');
    }

    public function handle()
    {
        $this->jobStart();

        $now = $this->now->copy();
        $month = $this->month;
        $year = $this->year;

        //Select all customers
        $customers = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
        ->where('m.type', '=', 'Electricity')
        ->where('m.status', '=', 'Installed')
        ->where('m.is_smart', '=', '0')
        ->where('m.has_validation_error', '=', '0')
        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
        ->get();
        $customersWithValidationError = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id') // Meters tied to customers that have no contract don't show up. They are marked with a validation error and not displayed through this.
        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
        ->where('m.type', '=', 'Electricity')
        ->where('m.status', '=', 'Installed')
        ->where('m.is_smart', '=', '0')
        ->where('m.has_validation_error', '=', '1')
        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
        ->get();
        // dd($customersWithValidationError);
        foreach($customersWithValidationError as $customerWithValidationError){
            $meter_id = $customerWithValidationError->mID;
            $this->logError(null, "The meter with id: $meter_id still has an active validation error. Mail is not generating for this meter.");
        }
        //Check if monthly or annual
        foreach($customers as $customer){
            $startContract = Carbon::parse($customer->startContract);

            //Check if annual last year
            $lastYearlyInvoice = Invoice::where('type', '=', 'Annual')
            ->where('meter_id', '=', $customer->mID)
            ->orderBy('invoice_date', 'desc')
            ->first();

            if(is_null($lastYearlyInvoice)){
                $invoiceCount = Invoice::where('meter_id', '=', $customer->mID)
                ->where('invoice_date', '>=', $customer->startContract)
                ->where('invoice_date', '<=', $now)
                ->count();
            } else {
                $invoiceCount = Invoice::where('meter_id', '=', $customer->mID)
                ->where('invoice_date', '>', $lastYearlyInvoice->invoice_date)
                ->where('invoice_date', '<=', $now)
                ->count();
            }

            //Monthly
            if($invoiceCount < 11){
                $invoiceDate = $startContract->addWeeks(2);
                $invoiceDate->setYear($year);
                $invoiceDate->setMonth($month);
        
                //Check if needs an invoice now
                if($invoiceDate == $now){
                    $dueDate = $invoiceDate->copy()->endOfMonth();

                    $this->generateMonthlyInvoice($customer, $invoiceDate, $dueDate);
                }

            } else { //Yearly
                $invoiceDate = $startContract->addYear()->addWeeks(2);
                $lastInvoiceDate = $invoiceDate->copy()->setYear($year-1);

                $invoiceDate->setYear($year);
                $invoiceDate->setMonth($month);
                $invoiceDate->setTimezone('Europe/Berlin');

                $missing = Meter::where('id', '=', $customer->mID)->first();

                //Rerun missing meter reading
                if($invoiceDate->copy()->addWeek() == $now && $missing->expecting_reading){
                    $this->generateYearlyInvoice($customer, $lastInvoiceDate, $now->copy());
                    Meter::where('id', '=', $customer->mID)
                    ->update(['expecting_reading' => 0]);
                }//Reminder index values 1 week prior invoice run
                elseif($invoiceDate->copy()->subWeek() == $now){
                    MeterReadingReminderJob::dispatch($customer->uID, $customer->mID);
                } //Check if needs an invoice now
                elseif($invoiceDate->copy() == $now){
                    $this->generateYearlyInvoice($customer, $lastInvoiceDate, $invoiceDate->copy()->addYear());
                }
            } 
        }
        $this->jobCompletion("Completed invoice run job");
    }

    public function generateYearlyInvoice($customer, $lastInvoiceDate, $nextInvoiceDate){
        $now = $this->now->copy();

        $meterReadings = Index_Value::where('meter_id', $customer->mID)
        ->where('reading_date', '>=', $lastInvoiceDate)
        ->where('reading_date', '<', $nextInvoiceDate)
        ->first();

        //Check if readings
        if(!is_null($meterReadings)){
            $consumption = User::join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
            ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
            ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.Address_id')
            ->join('Meters', 'Meter_addresses.Meter_id', '=', 'Meters.id')
            ->join('Index_values', 'Meters.id', '=', 'Index_values.meter_id')
            ->join('Consumptions', 'Index_values.id', '=', 'Consumptions.Current_index_id')
            ->where('reading_date', '>=', $lastInvoiceDate)
            ->where('reading_date', '<', $nextInvoiceDate)
            ->where('meters.id', '=', $customer->mID)
            ->select('Consumptions.*')
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

            $monthlyInvoices = $this->getMonthlyInvoices($customer->ccID, $customer->mID);

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
           
            $this->sendAnnualMail($invoice, $customer, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices);
            EstimationController::UpdateEstimation($customer->mID);  
        } else {
            dispatch(new MissingMeterReadingJob($customer->uID, $customer->mID));
        }
    }

    public function getMonthlyInvoices($cID, $mID) {
        $currentYear = Carbon::now()->year;

        // Query monthly invoices and their lines for the given customer and the current year
        $monthlyInvoices = Invoice::join('customer_contracts as cc', 'invoices.customer_contract_id', '=', 'cc.id')
            ->join('users as u', 'cc.user_id', '=', 'u.id')
            ->join('invoice_lines as il', 'invoices.id', '=', 'il.invoice_id')
            ->where('cc.id', $cID)
            ->where('invoices.meter_id', '=', $mID)
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

    public function sendAnnualMail(Invoice $invoice, $customer, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices)
    {
        $user = User::join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('a.is_billing_address', '=', 1)
        ->where('users.id', '=', $customer->uID)
        ->first();

        // Generate PDF
        $hash = md5($invoice->id . $invoice->customer_contract_id . $invoice->meter_id);

        $pdfData = [
            'invoice' => $invoice,
            'user' => $user,
            'consumption' => $consumption,
            'estimation' => $estimation,
            'newInvoiceLine' => $newInvoiceLine,
            'meterReadings' => $meterReadings,
            'discounts' => $discounts,
            'monthlyInvoices' => $monthlyInvoices,
            'domain' => $this->domain,
            'hash' => $hash
        ];

        $mailParams = [
            $invoice, 
            $user, 
            $pdfData, 
            $consumption,
            $estimation, 
            $newInvoiceLine, 
            $meterReadings, 
            $discounts, 
            $monthlyInvoices
        ];
        Log::info("QR code generated with link: " . $this->domain . "/pay/" . $invoice->id . "/" . $hash);

        //Send email with PDF attachment
        $this->sendMailInBackgroundWithPDF("ToCustomer@mail.com", AnnualInvoiceMail::class, $mailParams, 'Invoices.annual_invoice_pdf', $pdfData, $invoice->id);

    }

    public function generateMonthlyInvoice($customer, $invoiceDate, $invoiceDueDate){
        $month = $this->month;

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

        $productTariff = Product::join('product_tariffs as pt', 'pt.product_id', '=', 'products.id')
        ->join('tariffs as t', 't.id', '=', 'pt.id')
        ->where('products.id', '=', $contractProduct->pID)
        ->whereNull('pt.end_date')
        ->first();

        $invoiceData = [
            'invoice_date' => $invoiceDate->format('Y-m-d'),
            'due_date' => $invoiceDueDate->format('Y-m-d'),
            'total_amount' => 0,
            'status' => 'sent',
            'customer_contract_id' => $customer->ccID,
            'meter_id' => $customer->mID,
            'type' => 'Monthly'
        ];

        $invoice = Invoice::create($invoiceData);
        $lastInserted = $invoice->id;

        $scService = new StructuredCommunicationService;
        $strCom = $scService->generate($lastInserted);
        $scService->addStructuredCommunication($strCom, $lastInserted);

        $discount = Discount::where('contract_product_id', $customer->ccID)
        ->where(function ($query) use ($invoiceDate, $invoiceDueDate) {
            $query->where(function ($query) use ($invoiceDate, $invoiceDueDate) {
                    $query->whereDate('end_date', '>=', $invoiceDueDate)
                        ->whereDate('start_date', '<=', $invoiceDate->copy()->subWeeks(2));
                })
                ->orWhereNull('end_date')
                ->orWhere(function ($query) use ($invoiceDate, $invoiceDueDate) {
                    $query->where('end_date', '<', $invoiceDueDate)
                        ->whereDate('start_date', '<=', $invoiceDate->copy()->subWeeks(2));
                });
        })
        ->first();

        if (!is_null($discount)) {
            if(!is_null($discount->end_date)){
                $startDate = Carbon::create($discount->start_date);
                $endDate = Carbon::create($discount->end_date);

                $totalDays = $invoiceDate->subWeeks(2)->diffInDays($invoiceDueDate);
                $days = $startDate->diffInDays($endDate);

                // ex. 2 months = 31 - 60 = neg
                // ex. 2 weeks = 31 - 14 = pos

                if ($days > $totalDays && $endDate->month == $month) { //Overlapse into another month
                    $prevMonthDays = $startDate->copy()->endOfMonth()->day;
                    $thisMonthDays = $endDate->copy()->endOfMonth()->day;
                    
                    $days -= $prevMonthDays;
                    $discountRatio = $thisMonthDays - $days;
                } else {
                    $discountRatio = $totalDays - $days; 
                }

                if($discountRatio > 0) { //For # days of billing period

                    $discountPerDay = ($estimation / 12 * $discount->rate) / $totalDays;
                    $discountAmount = $discountPerDay * $days;

                    $estimatedPerDay = ($estimation / 12 * $productTariff->rate) / $totalDays;
                    $estimatedAmount = $estimatedPerDay * $discountRatio;

                    $totalAmount = $discountAmount + $estimatedAmount + 20;

                    $estimationPerDay = ($estimation / 12) / $totalDays;

                    Invoice_line::create([
                        'type' => "Electricity (for " . $discountRatio . "days)",
                        'unit_price' => $productTariff->rate,
                        'amount' => $estimationPerDay * $discountRatio,
                        'consumption_id' => null,
                        'invoice_id' => $lastInserted
                    ]);

                    Invoice_line::create([
                        'type' => "Electricity (discount for " . $days . " days)",
                        'unit_price' => $discount->rate,
                        'amount' => $estimationPerDay * $days,
                        'consumption_id' => null,
                        'invoice_id' => $lastInserted
                    ]);
                } else { //For whole billing period
                    $discountAmount = $estimation / 12 * $discount->rate;
                    $totalAmount = $discountAmount + 20;

                    Invoice_line::create([
                        'type' => 'Electricity (discount)',
                        'unit_price' => $discount->rate,
                        'amount' => $estimation / 12,
                        'consumption_id' => null,
                        'invoice_id' => $lastInserted
                    ]);
                }        
            } else {
                $discountAmount = $estimation / 12 * $discount->rate;
                $totalAmount = $discountAmount + 20;
                
                Invoice_line::create([
                    'type' => 'Electricity (discount)',
                    'unit_price' => $discount->rate,
                    'amount' => $estimation / 12,
                    'consumption_id' => null,
                    'invoice_id' => $lastInserted
                ]);
            }
        } else {
            $estimatedAmount = $estimation / 12 * $productTariff->rate;
            $totalAmount = $estimatedAmount + 20;           //Transport & distribution costs    

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $productTariff->rate,
                'amount' => $estimation / 12,
                'consumption_id' => null,
                'invoice_id' => $lastInserted
            ]);
        } 

        //Check for extra invoice lines
        $extraInvoiceLines = CreditNote::where('user_id', '=', $customer->uID)
        ->where('is_active', '=', 1)
        ->where('is_credit', '=', 1)
        ->where('is_applied', '=', 0)
        ->select('id', 'type', 'amount')
        ->get()->toArray();

        //Add to totalAmount
        if (sizeof($extraInvoiceLines) > 0){
            foreach ($extraInvoiceLines as $extraInvoiceLine) {
                $surplus = $extraInvoiceLine['amount'] + $totalAmount;
                $totalAmount += $extraInvoiceLine['amount'];

                if($totalAmount < 0){
                    $totalAmount = 0;
                }
            }
        }

        $invoice->total_amount = $totalAmount;
        $invoice->save();

        Invoice_line::create([
            'type' => 'Basic Service Fee',
            'unit_price' => 10.00,
            'amount' => 1,
            'consumption_id' => null,
            'invoice_id' => $lastInserted
        ]);

        Invoice_line::create([
            'type' => 'Distribution Fee',
            'unit_price' => 10.00,
            'amount' => 1,
            'consumption_id' => null,
            'invoice_id' => $lastInserted
        ]);

        //Add extra invoice line if there are any
        if (sizeof($extraInvoiceLines) > 0){
            foreach ($extraInvoiceLines as $extraInvoiceLine) {
                Invoice_line::create([
                    'type' => $extraInvoiceLine['type'],
                    'unit_price' => $extraInvoiceLine['amount'],
                    'amount' => 1,
                    'consumption_id' => null,
                    'invoice_id' => $lastInserted
                ]);

                if ($surplus < 0){
                    CreditNote::where('id', $extraInvoiceLine['id'])
                    ->update([
                        'amount' => $surplus,
                        'is_applied' => 1
                    ]);
                } else {
                    CreditNote::where('id', $extraInvoiceLine['id'])
                    ->update(['is_active' => 0]);
                }
            }
        }

        $fineService = new InvoiceFineService;
        $fineService->unpaidInvoiceFine($lastInserted);

        $newInvoiceLines = Invoice_line::where('invoice_id', '=', $lastInserted)->get();
        $this->sendMonthlyMail($invoice, $customer->uID, $newInvoiceLines);
    }

    public function sendMonthlyMail(Invoice $invoice, $uID, $newInvoiceLines)
    {
        $user = User::join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('a.is_billing_address', '=', 1)
        ->where('users.id', '=', $uID)
        ->first();
        
        // Generate PDF
        $hash = md5($invoice->id . $invoice->customer_contract_id . $invoice->meter_id);

        $pdfData = [
            'invoice' => $invoice,
            'user' => $user,
            'newInvoiceLines' => $newInvoiceLines,
            'domain' => $this->domain,
            'hash' => $hash
        ];

        $mailParams = [
            $invoice, 
            $user, 
            $newInvoiceLines
        ];

        Log::info("QR code generated with link: " . $this->domain . "/pay/" . $invoice->id . "/" . $hash);

        //Send email with PDF attachment
        $this->sendMailInBackgroundWithPDF("ToCustomer@mail.com", MonthlyInvoiceMail::class, $mailParams, 'Invoices.monthly_invoice_pdf', $pdfData, $invoice->id);

    }
}
