<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use App\Mail\weekAdvanceReminder;
use App\Mail\InvoiceDue;
use App\Mail\InvoiceFinalWarning;

use App\Models\Invoice_line;
use App\Models\User;

use App\Mail\MonthlyInvoiceMail;

use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Services\InvoiceFineService;

class advancemailcontroller extends Controller
{
    public function index()
    {
            //Query which invoices have not been paid yet and are due in 1 week
        $unpaidInvoices = Invoice::select('id')
        ->whereNotIn('status', ['paid', 'pending'])
        ->whereDate('due_date', '=', now()->addDays(7)->toDateString())
        ->get()
        ->pluck('id')
        ->toArray();

        //For each ID, send mail
        if (!empty($unpaidInvoices))
        {
            foreach ($unpaidInvoices as $unpaidInvoice)
            {
                $this->sendMail($unpaidInvoice);
            }
        }
    }

    public function sendMail(int $invoiceID)
    {
        //gather data of users: name, e-mail
        //gather data of lines of invoice
        $invoice = new Invoice();
        $invoice = Invoice::find($invoiceID);
        $invoice_info = $invoice->invoice_lines;

        $total_amount = Invoice::where('id', $invoiceID)->value('total_amount');

        $user_info = Invoice::select('users.email', 'users.first_name', 'users.last_name')
            ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
            ->where('invoices.id', $invoiceID)
            ->first();

        Mail::to('niki.de.visscher@gmail.com')->send(new weekAdvanceReminder($invoice_info, $total_amount, $user_info));
    }

    /*public function index()
    {
        //Query which invoices are due today
        $unpaidInvoices = Invoice::select('id')
        ->whereNotIn('status', ['paid', 'pending'])
        ->whereDate('due_date', '=', now()->toDateString())
        ->get()
        ->pluck('id')
        ->toArray();

        //For each ID, send mail
        if (!empty($unpaidInvoices))
        {
            foreach ($unpaidInvoices as $unpaidInvoice)
            {
                $this->sendMail($unpaidInvoice);
            }
        }
    }

    public function sendMail(int $invoiceID)
    {
        //gather data of users: name, e-mail
        //gather data of lines of invoice
        $invoice = new Invoice();
        $invoice = Invoice::find($invoiceID);
        $invoice_info = $invoice->invoice_lines;

        $total_amount = Invoice::where('id', $invoiceID)->value('total_amount');

        $user_info = Invoice::select('users.email', 'users.first_name', 'users.last_name')
            ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
            ->where('invoices.id', $invoiceID)
            ->first();

        Mail::to('niki.de.visscher@gmail.com')->send(new InvoiceDue($invoice_info, $total_amount, $user_info));
    }*/

    /*public function index()
    {
        //Query which invoices have not been paid yet and were due 1 week ago
        $unpaidInvoices = Invoice::select('id')
        ->whereNotIn('status', ['paid', 'pending'])
        ->whereDate('due_date', '=', now()->subDays(7)->toDateString())
        ->get()
        ->pluck('id')
        ->toArray();

        //For each ID, send mail
        if (!empty($unpaidInvoices))
        {
            foreach ($unpaidInvoices as $unpaidInvoice)
            {
                $this->sendMail($unpaidInvoice);
            }
        }
    }

    public function sendMail(int $invoiceID)
    {
        //gather data of users: name, e-mail
        //gather data of lines of invoice
        $invoice = new Invoice();
        $invoice = Invoice::find($invoiceID);
        $invoice_info = $invoice->invoice_lines;

        $total_amount = Invoice::where('id', $invoiceID)->value('total_amount');

        $user_info = Invoice::select('users.email', 'users.first_name', 'users.last_name')
            ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
            ->where('invoices.id', $invoiceID)
            ->first();

        Mail::to('niki.de.visscher@gmail.com')->send(new InvoiceFinalWarning($invoice_info, $total_amount, $user_info));
    }*/

    /*public function index()
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

            //Log::info($oldCustomer);

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

            $fineService = new InvoiceFineService;
            $fineService->unpaidInvoiceFine($lastInserted);

            $newInvoiceLines = Invoice_line::where('invoice_id', '=', $lastInserted)->get();
            advancemailcontroller::sendMail($invoice, $customer->uID, $estimation, $newInvoiceLines);
    }

    public function sendMail(Invoice $invoice, $uID, $estimation, $newInvoiceLines)
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
            'estimation' => $estimation,
            'newInvoiceLines' => $newInvoiceLines,
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        Mail::to('niki.de.visscher@gmail.com')->send(new MonthlyInvoiceMail(
            $invoice, $user, $pdfData, $estimation, $newInvoiceLines
        ));

    }*/
}
