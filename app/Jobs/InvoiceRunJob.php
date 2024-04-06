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
    Discount
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

use App\Mail\AnnualInvoiceMail;
use App\Mail\MonthlyInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Services\InvoiceFineService;

class InvoiceRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $now = Carbon::create(2025, 1, 15);
        //$now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        //Select all customers
        $customers = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
        ->get();

        //Check if monthly or annual
        foreach($customers as $customer){
            $startContract = Carbon::parse($customer->startContract);

            //Check if annual last year
            if($startContract->year == $year){ //New Customer
                $invoiceCount = Invoice::where('meter_id', '=', $customer->mID)
                ->where('invoice_date', '>=', $customer->startContract)
                ->where('invoice_date', '<=', $now)
                ->count();
            } else { //Old Customer
                $lastYearlyInvoice = Invoice::where('type', '=', 'Annual')
                ->where('meter_id', '=', $customer->mID)
                ->orderBy('invoice_date', 'desc')
                ->first();

                $invoiceCount = Invoice::where('meter_id', '=', $customer->mID)
                ->where('invoice_date', '>', $lastYearlyInvoice->invoice_date)
                ->where('invoice_date', '<=', $now)
                ->count();
            }

            //Monthly
            if($invoiceCount < 11){
                //New Customer (this month)
                if($startContract->year == $year && $startContract->month == $month){
                    //Check if needs an invoice now
                    if($startContract->addWeeks(2) == $now){
                        $invoiceDate = $now;
                        $dueDate = $invoiceDate->copy()->addWeeks(2);
            
                        $formattedInvoiceDate = $invoiceDate->toDateString();
                        $formattedDueDate = $dueDate->toDateString();
                        
                        $this->generateMonthlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate);
                    }
                } else { //Old Customer
                    $lastInvoice = Invoice::where('meter_id', '=', $customer->mID)
                    ->orderBy('invoice_date', 'desc')
                    ->first();

                    $lastInvoiceDate = Carbon::parse($lastInvoice->invoice_date);

                    //Check if needs an invoice now
                    if($lastInvoiceDate->addWeeks(2) == $now){
                        dd('monthly');
                        $invoiceDate = $now;
                        $dueDate = $invoiceDate->copy()->addWeeks(2);
        
                        $formattedInvoiceDate = $invoiceDate->toDateString();
                        $formattedDueDate = $dueDate->toDateString();
        
                        $this->generateMonthlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate);
                    }
                }

            } else { //Yearly
                //New Customer
                if($startContract->year == $year){
                    //Check if needs an invoice now
                    if($startContract->addYear()->addWeeks(2) == $now){
                        $invoiceDate = $now;
                        $dueDate = $invoiceDate->copy()->addWeeks(2);
        
                        $formattedInvoiceDate = $invoiceDate->toDateString();
                        $formattedDueDate = $dueDate->toDateString();
        
                        $this->generateYearlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate, $year);
                    }

                } else { //old Customer
                    $lastInvoiceDate = Carbon::parse($lastYearlyInvoice->invoice_date);

                    //Check if needs an invoice now
                    if($lastInvoiceDate->addYear() == $now){
                        $invoiceDate = $now;
                        $dueDate = $invoiceDate->copy()->addWeeks(2);
        
                        $formattedInvoiceDate = $invoiceDate->toDateString();
                        $formattedDueDate = $dueDate->toDateString();
        
                        $this->generateYearlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate);
                    }
                }
            } 

        }

    }

    public function generateYearlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate){
        $now = Carbon::create(2024, 3, 15);
        //$now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        $readings = Index_Value::where('meter_id', '=', $customer->mID)
        ->whereYear('reading_date', '=', $year)
        ->get();

        //Check if readings
        if(!is_null($readings)){
            $consumption = User::join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
            ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
            ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.Address_id')
            ->join('Meters', 'Meter_addresses.Meter_id', '=', 'Meters.id')
            ->join('Index_values', 'Meters.id', '=', 'Index_values.meter_id')
            ->join('Consumptions', 'Index_values.id', '=', 'Consumptions.Current_index_id')
            ->whereYear('Index_values.Reading_date', '=', $year)
            ->where('meters.id', '=', $customer->mID)
            ->select('Consumptions.*')
            ->first();

            $meterReadings = Index_Value::where(function ($query) use ($year) {
                $query->whereYear('reading_date', $year)
                    ->orWhereYear('reading_date', $year - 1);
            })
            ->where('meter_id', '=', $customer->mID)
            ->select('reading_value')
            ->get();

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
            ->whereYear('discounts.start_date', '=', $year)
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
    
                    $monthlyExtraAmount = ($consumption->consumption_value / 12) * $productTariff->rate;
    
                    if ($discountRate > 0) {
                        $monthlyExtraAmount -= ($monthlyExtraAmount * $discountRate);
                    }
                
                    $extraAmount += $monthlyExtraAmount;
                }
            } else {
                $extraAmount = ($consumption->consumption_value) ? $consumption->consumption_value * $productTariff->rate : 0;
            }

            $monthlyInvoices = $this->getMonthlyInvoices($customer->ccID);

            if($extraAmount > 0){                   //Invoice
                $invoiceData = [
                    'invoice_date' => $now->format('Y/m/d'),
                    'due_date' => $now->copy()->addWeeks(2)->format('Y/m/d'),
                    'total_amount' => $extraAmount,
                    'status' => 'sent',
                    'customer_contract_id' => $customer->ccID,
                    'meter_id' => $customer->mID,
                    'type' => 'Annual'
                ];
            } else{                              //Credit note
                $invoiceData = [
                    'invoice_date' => $now->format('Y/m/d'),
                    'due_date' => $now->copy()->addWeeks(2)->format('Y/m/d'),
                    'total_amount' => $extraAmount,
                    'status' => 'paid',
                    'customer_contract_id' => $customer->ccID,
                    'meter_id' => $customer->mID,
                    'type' => 'Annual'
                ];

                CreditNote::create([
                    'type' => 'credit note',
                    'amount' => $extraAmount,
                    'user_id' => $customer->uID
                ]);
            }

            $invoice = Invoice::create($invoiceData);
            $lastInserted = $invoice->id;

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $productTariff->rate,
                'amount' => $extraAmount,
                'consumption_id' => $consumption->id,
                'invoice_id' => $lastInserted
            ]);
            
            $newInvoiceLine = Invoice_line::where('invoice_id', '=', $lastInserted)->first();
           
            $this->sendAnnualMail($invoice, $customer, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices);
            EstimationController::UpdateAllEstimation();  
        } else {
            //Missing Meter readings
        }
    }

    public function getMonthlyInvoices($cID) {
        $currentYear = Carbon::now()->year;

        // Query monthly invoices and their lines for the given customer and the current year
        $monthlyInvoices = Invoice::join('customer_contracts as cc', 'invoices.customer_contract_id', '=', 'cc.id')
            ->join('users as u', 'cc.user_id', '=', 'u.id')
            ->join('invoice_lines as il', 'invoices.id', '=', 'il.invoice_id')
            ->where('cc.id', $cID)
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
        $pdf = Pdf::loadView('Invoices.annual_invoice_pdf', [
            'invoice' => $invoice,
            'user' => $user,
            'consumption' => $consumption,
            'estimation' => $estimation,
            'newInvoiceLine' => $newInvoiceLine,
            'meterReadings' => $meterReadings,
            'discounts' => $discounts,
            'monthlyInvoices' => $monthlyInvoices
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        Mail::to('shaunypersy10@gmail.com')->send(new AnnualInvoiceMail(
            $invoice, $user, $pdfData, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices
        ));

    }

    public function generateMonthlyInvoice($customer, $invoiceDate, $invoiceDueDate){
        $estimationResult = User::join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
        ->join('addresses as a', 'ca.address_id', '=', 'a.id')
        ->join('meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('meters as m', 'ma.meter_id', '=', 'm.id')
        ->join('estimations as e', 'e.meter_id', '=', 'm.id')
        ->where('users.id', '=', $customer->uID)
        ->select('e.estimation_total')
        ->first();

        $estimation = $estimationResult->estimation_total;

        $contractProduct = Contract_product::join('products as p', 'p.id', '=', 'contract_products.product_id')
        ->leftjoin('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
        ->leftjoin('tariffs as t', 'pt.tariff_id', '=', 't.id')
        ->where('customer_contract_id', '=', $customer->ccID)
        ->whereNull('contract_products.end_date')
        ->select('contract_products.id as cpID', 'contract_products.start_date as cpStartDate', 'p.product_name as productName',
        'p.id as pID', 't.id as tID')
        ->first();

        //without discounts
        $productTariff = Product::join('product_tariffs as pt', 'pt.product_id', '=', 'products.id')
        ->join('tariffs as t', 't.id', '=', 'pt.id')
        ->where('products.id', '=', $contractProduct->pID)
        ->whereNull('pt.end_date')
        ->first();

        $estimatedAmount = $estimation / 12 * $productTariff->rate;
        $totalAmount = $estimatedAmount + 20;           //Transport & distribution costs

        //Check for extra invoice lines
        $extraInvoiceLines = User::join('credit_notes as cn', 'cn.user_id', '=', 'users.id')
        ->where('users.id', '=', $customer->uID)
        ->where('cn.is_active', '=', 1)
        ->where('cn.is_credit', '=', 0)
        ->select('cn.id', 'cn.type', 'cn.amount')
        ->get()->toArray();

        //Add to totalamount
        if (sizeof($extraInvoiceLines) > 0){
            foreach ($extraInvoiceLines as $extraInvoiceLine) {
                $totalAmount += $extraInvoiceLine->amount;
            }
        }

        $invoiceData = [
            'invoice_date' => $invoiceDate,
            'due_date' => $invoiceDueDate,
            'total_amount' => $totalAmount,
            'status' => 'sent',
            'customer_contract_id' => $customer->ccID,
            'meter_id' => $customer->mID,
            'type' => 'Monthly'
        ];

        $invoice = Invoice::create($invoiceData);
        $lastInserted = $invoice->id;

        Invoice_line::create([
            'type' => 'Electricity',
            'unit_price' => $productTariff->rate,
            'amount' => $estimation,
            'consumption_id' => null,
            'invoice_id' => $lastInserted
        ]);

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
                    'type' => $extraInvoiceLine->type,
                    'unit_price' => $extraInvoiceLine->amount,
                    'amount' => 1,
                    'consumption_id' => null,
                    'invoice_id' => $lastInserted
                ]);
                CreditNote::where('id', $extraInvoiceLine->id)
                ->update(['is_active' => 0]);
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
        $pdf = Pdf::loadView('Invoices.monthly_invoice_pdf', [
            'invoice' => $invoice,
            'user' => $user,
            'newInvoiceLines' => $newInvoiceLines,
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        Mail::to('shaunypersy10@gmail.com')->send(new MonthlyInvoiceMail(
            $invoice, $user, $pdfData, $newInvoiceLines
        ));

    }
}
