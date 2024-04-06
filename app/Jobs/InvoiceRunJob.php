<?php

namespace App\Jobs;

use App\Models\{
    User, 
    Invoice,
    Contract_product,
    Product,
    Invoice_line,
    CreditNote
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

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
        $now = Carbon::create(2024, 3, 15);
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
            dd($customer);
            //Count all invoices of this year
            $invoiceCount = Invoice::where('meter_id', '=', $customer->mID)
            ->where('type', '=', 'Monthly')
            ->whereYear('invoice_date', '=', $year)
            ->get()
            ->count();

            $startContract = Carbon::parse($customer->startContract);

            //Monthly
            if($invoiceCount <= 12){
                //New Customer (this month)
                if($startContract->year == $year && $startContract->month == $month){
                    //Check if needs an invoice now
                    if($startContract->addMonth() == $now){
                        $invoiceDate = $now;
                        $dueDate = $invoiceDate->copy()->addWeeks(2);
            
                        $formattedInvoiceDate = $invoiceDate->toDateString();
                        $formattedDueDate = $dueDate->toDateString();
                        
                        $this->generateMonthlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate);
                    }
                } else { //Old Customer
                    $lastInvoice = Invoice::where('type', '=', 'Monthly')
                    ->whereYear('invoice_date', '=', $year)
                    ->where('customer_contract_id', '=', $customer->ccID)
                    ->orderBy('invoice_date', 'desc')
                    ->first();

                    $lastInvoiceDate = Carbon::parse($lastInvoice->invoice_date);

                    //Check if needs an invoice now
                    if($lastInvoiceDate->addMonth() == $now){
                        dd('yes');
                        $lastDueDate = Carbon::parse($lastInvoice->due_date);
                        $newInvoiceDate = $lastDueDate->copy()->addWeeks(2);
                        $newInvoiceDueDate = $newInvoiceDate->copy()->addWeeks(2);
        
                        $formattedInvoiceDate = $newInvoiceDate->toDateString();
                        $formattedDueDate = $newInvoiceDueDate->toDateString();
        
                        $this->generateMonthlyInvoice($customer, $formattedInvoiceDate, $formattedDueDate);
                    }
                }

            } else { //Yearly

            } 

        }

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
        ->first();

        $estimatedAmount = $estimation * $productTariff->rate;
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
        Mail::to('yannick.strackx@gmail.com')->send(new MonthlyInvoiceMail(
            $invoice, $user, $pdfData, $newInvoiceLines
        ));

    }
}
