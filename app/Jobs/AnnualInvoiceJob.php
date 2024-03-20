<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Mail\meter_reading_notice;
use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\Address;
use App\Mail\AnnualInvoiceMail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class AnnualInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //\Log::info('regular job executed successfully!');

        //Aquire the current month
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        $currentDate = $now->format('Y/m/d');

        $endOfMonth = Carbon::now()->endOfMonth();
        $yearAgo = $now->subYear();
        
        // Query for users that started a contract this year (base yearly invoice of start contract)
        /*$customers = DB::table('users')
        ->join('Customer_contracts', 'users.id', '=', 'Customer_contracts.user_id')
        ->whereYear('Customer_contracts.start_date', '=', $year)
        ->select('users.*')
        ->get();

        //New Customers that need to be invoiced now
        foreach($customers as $newCustomer){
            $newCustomersInfo = DB::table('users')
                ->join('Customer_contracts', 'users.id', '=', 'Customer_contracts.user_id')
                ->whereYear('Customer_contracts.start_date', '=', $yearAgo->year)
                ->whereMonth('Customer_contracts.start_date', '=', $yearAgo->month)
                ->whereDay('Customer_contracts.start_date', '=', $yearAgo->day)
                ->where('Customer_contracts.id', '=', $newCustomer->id)
                ->select('users.*')
                ->get();
        }

        // Query for users that started a contract before this year (base yearly invoice of the date of the previous yearly invoice)
        $oldCustomers = DB::table('users')
            ->join('Customer_contracts', 'users.id', '=', 'Customer_contracts.user_id')
            ->whereYear('Customer_contracts.start_date', '!=', $year)
            ->select('users.*')
            ->get();

        $oldCustomerInfo = [];

        // Old Customers that need to be invoiced now
        foreach($oldCustomers as $oldCustomer){
            $oldCustomerInfo[] = Invoice::join('customer_contracts as cc', 'cc.id', '=', 'invoices.customer_contract_id')
                ->join('users as u', 'u.id', '=', 'cc.user_id')
                ->where('type', '=', 'Annual')
                ->latest()
                ->first();
        }

        */

        // then we have to check whether we have their meter readings
        $customersWithReadings = DB::table('users')
        ->join('Customer_contracts', 'users.id', '=', 'customer_contracts.user_id')
        ->join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
        ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
        ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.address_id')
        ->join('Meters', 'Meter_addresses.meter_id', '=', 'Meters.id')
        ->join('Index_values', 'Meters.id', '=', 'Index_values.meter_id')
        ->whereYear('Index_values.reading_date', '=', $year)
        ->distinct()
        ->select('users.id as uID', 'customer_contracts.id as ccID', 'meters.id as mID')
        ->get();

        foreach($customersWithReadings as $customer){
            $consumptions = DB::table('users')
            ->join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
            ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
            ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.Address_id')
            ->join('Meters', 'Meter_addresses.Meter_id', '=', 'Meters.id')
            ->join('Index_values', 'Meters.id', '=', 'Index_values.meter_id')
            ->join('Consumptions', 'Index_values.id', '=', 'Consumptions.Current_index_id')
            ->whereYear('Index_values.Reading_date', '=', $year)
            ->where('meters.id', '=', $customer->mID)
            ->select('Consumptions.*')
            ->get();

            $estimationResult = DB::table('users as u')
            ->join('customer_addresses as ca', 'ca.user_id', '=', 'u.id')
            ->join('addresses as a', 'ca.address_id', '=', 'a.id')
            ->join('meter_addresses as ma', 'a.id', '=', 'ma.address_id')
            ->join('meters as m', 'ma.meter_id', '=', 'm.id')
            ->join('estimations as e', 'e.meter_id', '=', 'm.id')
            ->where('m.id', '=', $customer->mID)
            ->select('e.estimation_total')
            ->first();

            $estimation = $estimationResult->estimation_total;

            $contractProduct = DB::table('contract_products as cp')
            ->select('cp.id as cpID', 'cp.start_date as cpStartDate', 'p.product_name as productName',
            'p.id as pID', 't.id as tID')
            ->join('products as p', 'p.id', '=', 'cp.product_id')
            ->leftjoin('tariffs as t', 't.id', '=', 'cp.tariff_id')
            ->where('customer_contract_id', '=', $customer->ccID)
            ->whereNull('cp.end_date')
            ->first();

            //without discounts
            $productTariff = DB::table('products as p')
            ->join('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
            ->join('tariffs as t', 't.id', '=', 'pt.id')
            ->where('p.id', '=', $contractProduct->pID)
            ->first();

            $extraAmounts = [];

            foreach($consumptions as $consumption){
                $extraAmount = $consumption->consumption_value * $productTariff->rate;
                $extraAmounts[] = $extraAmount;
            }

            $totalExtraAmount = 0;

            foreach($extraAmounts as $extraAmount){
                $totalExtraAmount += $extraAmount;
            }

            $invoiceData = [
                'invoice_date' => $now->format('Y/m/d'),
                'due_date' => $endOfMonth->format('Y/m/d'),
                'total_amount' => $totalExtraAmount,
                'status' => 'sent',
                'customer_contract_id' => $customer->ccID,
                'type' => 'Annual'
            ];

            $invoice = Invoice::create($invoiceData);
            $lastInserted = $invoice->id;

            $i = 0;

            foreach($consumptions as $consumption){
                Invoice_line::create([
                    'type' => 'Electricity',
                    'unit_price' => $productTariff->rate,
                    'amount' => $extraAmounts[$i],
                    'consumption_id' => $consumption->id,
                    'invoice_id' => $lastInserted
                ]);

                $i++;
            }

            $newInvoiceLines = Invoice_line::where('invoice_id', '=', $lastInserted)->get();
           
            AnnualInvoiceJob::sendMail($invoice, $customer, $consumptions, $estimation, $newInvoiceLines);
        }
        
    }

    public function sendMail(Invoice $invoice, $customer, $consumptions, $estimation, $newInvoiceLines)
    {
        $user = DB::table('users as u')
        ->join('customer_addresses as ca', 'ca.user_id', '=', 'u.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('a.is_billing_address', '=', 1)
        ->where('u.id', '=', $customer->uID)
        ->first();
        
        // Generate PDF
        $pdf = Pdf::loadView('Invoices.annual_invoice_pdf', [
            'invoice' => $invoice,
            'user' => $user,
            'consumptions' => $consumptions,
            'estimation' => $estimation,
            'newInvoiceLines' => $newInvoiceLines,
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        Mail::to('shaunypersy10@gmail.com')->send(new AnnualInvoiceMail(
            $invoice, $user, $pdfData, $consumptions, $estimation, $newInvoiceLines
        ));

    }
}
