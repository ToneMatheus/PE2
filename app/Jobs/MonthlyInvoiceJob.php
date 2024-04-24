<?php

namespace App\Jobs;

use App\Models\Estimation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\User;
use App\Models\CreditNote;

use App\Mail\MonthlyInvoiceMail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Services\InvoiceFineService;

class MonthlyInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
      
    }

    public function handle()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');


       //Query new customers (this month)       
       $newCustomers = User::join('customer_contracts as cc', 'cc.user_id', '=', 'users.id')
       ->join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
       ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
       ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.address_id')
       ->join('Meters', 'Meter_addresses.meter_id', '=', 'Meters.id')
       ->whereMonth('cc.start_date', '=', $month)
       ->whereYear('cc.start_date', '=', $year)
       ->get();

       //Query old customers
       $oldCustomers = User::join('customer_contracts as cc', 'cc.user_id', '=', 'users.id')
       ->join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
       ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
       ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.address_id')
       ->join('Meters', 'Meter_addresses.meter_id', '=', 'Meters.id')
        ->where(function($query) use ($month, $year) {
            $query->whereYear('cc.start_date', '<', $year)
                ->orWhere(function($query) use ($month, $year) {
                    $query->whereYear('cc.start_date', '=', $year)
                        ->whereMonth('cc.start_date', '<', $month);
                });
        })
        ->select('cc.id as ccID','cc.start_date as start_date' ,'users.id as uID')
        ->get();

        //add 2 weeks to start_date of contract
        if(!is_null($newCustomers)){
            foreach($newCustomers as $newCustomer){
                $startDate = Carbon::parse($newCustomer->start_date);
                $invoiceDate = $startDate->copy()->addWeeks(2);
                $dueDate = $invoiceDate->copy()->addWeeks(2);
    
                $formattedInvoiceDate = $invoiceDate->toDateString();
                $formattedDueDate = $dueDate->toDateString();
                
                $this->generateInvoice($newCustomer, $formattedInvoiceDate, $formattedDueDate);
            }
        }

        //add 2 weeks to due_date of last monthly invoice
        foreach($oldCustomers as $oldCustomer){
            //Query amount of invoice this year
            $amountInvoices = Invoice::where('type', '=', 'Monthly')
            ->whereYear('invoices.invoice_date', '=', $year)
            ->where('invoices.customer_contract_id', '=', $oldCustomer->ccID)
            ->count();

            Log::info($oldCustomer);

            if($amountInvoices < 12) {
                //Query last monthly invoice
                $lastInvoice = Invoice::where('type', '=', 'Monthly')
                ->whereYear('invoice_date', '=', $year)
                ->where('customer_contract_id', '=', $oldCustomer->ccID)
                ->orderBy('invoice_date', 'desc')
                ->first();

                $lastDueDate = Carbon::parse($lastInvoice->due_date);
                $newInvoiceDate = $lastDueDate->copy()->addWeeks(2);
                $newInvoiceDueDate = $newInvoiceDate->copy()->addWeeks(2);

                $formattedInvoiceDate = $newInvoiceDate->toDateString();
                $formattedDueDate = $newInvoiceDueDate->toDateString();

                $this->generateInvoice($oldCustomer, $formattedInvoiceDate, $formattedDueDate);
            }
        }
    }

    public function generateInvoice($customer, $invoiceDate, $invoiceDueDate){
        $estimationResult = DB::table('users as u')
            ->join('customer_addresses as ca', 'ca.user_id', '=', 'u.id')
            ->join('addresses as a', 'ca.address_id', '=', 'a.id')
            ->join('meter_addresses as ma', 'a.id', '=', 'ma.address_id')
            ->join('meters as m', 'ma.meter_id', '=', 'm.id')
            ->join('estimations as e', 'e.meter_id', '=', 'm.id')
            ->where('u.id', '=', $customer->uID)
            ->select('e.estimation_total')
            ->first();

            $estimation = $estimationResult->estimation_total;

            //tariff_id no longer in contract_products table, see edited query below
            /*$contractProduct = DB::table('contract_products as cp')
            ->select('cp.id as cpID', 'cp.start_date as cpStartDate', 'p.product_name as productName',
            'p.id as pID', 't.id as tID')
            ->join('products as p', 'p.id', '=', 'cp.product_id')
            ->leftjoin('tariffs as t', 't.id', '=', 'cp.tariff_id')
            ->where('customer_contract_id', '=', $customer->ccID)
            ->whereNull('cp.end_date')
            ->first();*/

            $contractProduct = DB::table('contract_products as cp')
            ->select('cp.id as cpID', 'cp.start_date as cpStartDate', 'p.product_name as productName',
            'p.id as pID', 't.id as tID')
            ->join('products as p', 'p.id', '=', 'cp.product_id')
            ->leftjoin('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
            ->leftjoin('tariffs as t', 'pt.tariff_id', '=', 't.id')
            ->where('customer_contract_id', '=', $customer->ccID)
            ->whereNull('cp.end_date')
            ->first();

            //without discounts
            $productTariff = DB::table('products as p')
            ->join('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
            ->join('tariffs as t', 't.id', '=', 'pt.id')
            ->where('p.id', '=', $contractProduct->pID)
            ->first();

            $estimatedAmount = $estimation * $productTariff->rate;
            $totalAmount = $estimatedAmount + 20;           //Transport & distribution costs

            //Check for extra invoice lines
            $extraInvoiceLines = DB::table('users as u')
            ->select('cn.id', 'cn.type', 'cn.amount')
            ->join('credit_notes as cn', 'cn.user_id', '=', 'u.id')
            ->where('u.id', '=', $customer->uID)
            ->where('cn.is_active', '=', 1)
            ->where('cn.is_credit', '=', 0)
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
            MonthlyInvoiceJob::sendMail($invoice, $customer->uID, $newInvoiceLines);
    }

    public function sendMail(Invoice $invoice, $uID, $newInvoiceLines)
    {
        $user = DB::table('users as u')->join('customer_addresses as ca', 'ca.user_id', '=', 'u.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('a.is_billing_address', '=', 1)
        ->where('u.id', '=', $uID)
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

