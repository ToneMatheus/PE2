<?php

namespace App\Jobs;

use App\Models\Estimation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\User;

use App\Mail\MonthlyInvoiceMail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
       ->whereMonth('cc.start_date', '=', $month)
       ->whereYear('cc.start_date', '=', $year)
       ->get();

       //Query old customers
       $oldCustomers = User::join('customer_contracts as cc', 'cc.user_id', '=', 'users.id')
        ->where(function($query) use ($month, $year) {
            $query->whereYear('cc.start_date', '<', $year)
                ->orWhere(function($query) use ($month, $year) {
                    $query->whereYear('cc.start_date', '=', $year)
                        ->whereMonth('cc.start_date', '<', $month);
                });
        })
        ->get();

        //add 2 weeks to start_date of contract
        foreach($newCustomers as $newCustomer){
            $startDate = Carbon::parse($newCustomer->start_date);
            $invoiceDate = $startDate->copy()->addWeeks(2);
            $dueDate = $invoiceDate->copy()->addWeeks(2);

            $formattedInvoiceDate = $invoiceDate->toDateString();
            $formattedDueDate = $dueDate->toDateString();
            
            //$this->generateInvoice($newCustomer, $formattedInvoiceDate, $formattedDueDate);
        }

        //add 2 weeks to due_date of last monthly invoice
        foreach($oldCustomers as $oldCustomer){
            //Query amount of invoice this year
            $amountInvoices = Invoice::where('type', '=', 'Monthly')
            ->whereYear('invoices.invoice_date', '=', $year)
            ->where('invoices.customer_contract_id', '=', $oldCustomer->id)
            ->count();

            if($amountInvoices < 12) {
                //Query last monthly invoice
                $lastInvoice = Invoice::where('type', '=', 'Monthly')
                ->whereYear('invoice_date', '=', $year)
                ->where('customer_contract_id', '=', $oldCustomer->id)
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
            ->where('u.id', '=', $customer->user_id)
            ->select('e.estimation_total')
            ->first();

            $estimation = $estimationResult->estimation_total;

            $contractProduct = DB::table('contract_products as cp')
            ->select('cp.id as cpID', 'cp.start_date as cpStartDate', 'p.product_name as productName',
            'p.id as pID', 't.id as tID')
            ->join('products as p', 'p.id', '=', 'cp.product_id')
            ->leftjoin('tariffs as t', 't.id', '=', 'cp.tariff_id')
            ->where('customer_contract_id', '=', $customer->id)
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

            $invoiceData = [
                'invoice_date' => $invoiceDate,
                'due_date' => $invoiceDueDate,
                'total_amount' => $totalAmount,
                'status' => 'sent',
                'customer_contract_id' => $customer->id,
                'type' => 'Monthly'
            ];

            $invoice = Invoice::create($invoiceData);
            $lastInserted = $invoice->id;

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $productTariff->rate,
                'amount' => $estimatedAmount,
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

            $newInvoiceLines = Invoice_line::where('invoice_id', '=', $lastInserted)->get();
            MonthlyInvoiceJob::sendMail($invoice, $customer->user_id, $estimation, $newInvoiceLines);
    }

    public function sendMail(Invoice $invoice, $cID, $estimation, $newInvoiceLines)
    {
        $user = DB::table('users as u')->join('customer_addresses as ca', 'ca.user_id', '=', 'u.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('u.id', '=', $cID)
        ->first();
        
        // Generate PDF
        $pdf = Pdf::loadView('Invoices.monthly_invoice_pdf', [
            'invoice' => $invoice,
            'user' => $user,
            'estimation' => $estimation,
            'newInvoiceLines' => $newInvoiceLines,
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        Mail::to('shaunypersy10@gmail.com')->send(new MonthlyInvoiceMail(
            $invoice, $user, $pdfData, $estimation, $newInvoiceLines
        ));

    }
}

