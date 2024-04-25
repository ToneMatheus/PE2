<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\CreditNote;
use App\Mail\AnnualInvoiceMail;
use App\Http\Controllers\EstimationController;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\cronJobTrait;


class AnnualInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    public function handle()
    {

        $this->jobStart();
        //Aquire the current month
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

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
        ->select('users.id as uID', 'customer_contracts.id as ccID', 'meters.id as mID')
        ->get();

        foreach($customersWithReadings as $customer){
            $consumption = DB::table('users')
            ->join('Customer_addresses', 'users.id', '=', 'Customer_addresses.user_id')
            ->join('Addresses', 'Customer_addresses.Address_id', '=', 'Addresses.id')
            ->join('Meter_addresses', 'Addresses.id', '=', 'Meter_addresses.Address_id')
            ->join('Meters', 'Meter_addresses.Meter_id', '=', 'Meters.id')
            ->join('Index_values', 'Meters.id', '=', 'Index_values.meter_id')
            ->join('Consumptions', 'Index_values.id', '=', 'Consumptions.Current_index_id')
            ->whereYear('Index_values.Reading_date', '=', $year)
            ->where('meters.id', '=', $customer->mID)
            ->select('Consumptions.*')
            ->first();

            $meterReadings = DB::table('index_values')
            ->where(function ($query) use ($year) {
                $query->whereYear('reading_date', $year)
                    ->orWhereYear('reading_date', $year - 1);
            })
            ->where('meter_id', '=', $customer->mID)
            ->select('reading_value')
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
            'p.id as pID')
            ->join('products as p', 'p.id', '=', 'cp.product_id')
            ->where('customer_contract_id', '=', $customer->ccID)
            ->where('meter_id', '=', $customer->mID)
            ->whereNull('cp.end_date')
            ->first();

            $discounts = DB::table('discounts as d')
            ->where('d.contract_product_id', '=', $contractProduct->cpID)
            ->whereYear('d.start_date', '=', $year)
            ->whereDate('d.end_date', '>=', $now->format('Y/m/d'))
            ->get();

            $productTariff = DB::table('products as p')
            ->join('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
            ->join('tariffs as t', 't.id', '=', 'pt.id')
            ->where('p.id', '=', $contractProduct->pID)
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

            $monthlyInvoices = AnnualInvoiceJob::getMonthlyInvoices($customer->ccID);

            if($extraAmount > 0){                   //Invoice
                $invoiceData = [
                    'invoice_date' => $now->format('Y/m/d'),
                    'due_date' => $now->copy()->addWeeks(2)->format('Y/m/d'),
                    'total_amount' => $extraAmount,
                    'status' => 'sent',
                    'customer_contract_id' => $customer->ccID,
                    'type' => 'Annual'
                ];
            } else{                                 //Credit note
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
           
            AnnualInvoiceJob::sendMail($invoice, $customer, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices);
            EstimationController::UpdateAllEstimation();  
        }
        $this->jobCompletion("Finished annual invoice");
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

    public function sendMail(Invoice $invoice, $customer, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices)
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
            'consumption' => $consumption,
            'estimation' => $estimation,
            'newInvoiceLine' => $newInvoiceLine,
            'meterReadings' => $meterReadings,
            'discounts' => $discounts,
            'monthlyInvoices' => $monthlyInvoices
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        $this->sendMailInBackground("ToCustomer@mail.com", AnnualInvoiceMail::class, [$invoice, $user, $pdfData, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices], $invoice->id);

    }
}
