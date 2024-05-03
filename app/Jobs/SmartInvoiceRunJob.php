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
use App\Mail\SmartInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\cronJobTrait;

use App\Services\InvoiceFineService;
use App\Services\StructuredCommunicationService;

class SmartInvoiceRunJob implements ShouldQueue 
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

        //Select all customers with smart meter
        $customers = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
        ->where('m.type', '=', 'Electricity')
        ->where('m.status', '=', 'Installed')
        ->where('m.is_smart', '=', '1')
        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
        ->get();
        // dd($customers);
        // no check needed for monthly or yearly. Only one type: smart.
        foreach($customers as $customer){
            $startContract = Carbon::parse($customer->startContract);
            // $missing = Meter::where('id', '=', $customer->mID)->first();
            // dd($missing);
            $invoiceDate = $startContract->addWeeks(2);
            $invoiceDate->setYear($year);
            $invoiceDate->setMonth($month);
            if($invoiceDate == $now){
                $dueDate = $invoiceDate->copy()->endOfMonth();
                $this->generateSmartInvoice($customer, $invoiceDate, $dueDate);
            }else{
                // dd("test");
                $dueDate = $invoiceDate->copy()->endOfMonth();
                $this->generateSmartInvoice($customer, $invoiceDate, $dueDate);
            }

        }
        $this->jobCompletion("Completed invoice run job");
    }
    

    public function generateSmartInvoice($customer, $invoiceDate, $invoiceDueDate)
    {
        if ($this->month == 1) {
            $previousMonth = 12;
            $previousYear = $this->year-1;
        } else {
            $previousMonth = $this->month-1;
            $previousYear = $this->year;
        }
        $meterReadings = Index_Value::where('meter_id', $customer->mID)
        ->whereMonth("reading_date", $this->month)
        ->whereYear("reading_date", $this->year)
        ->select("reading_value")
        ->first();
        // dd($meterReadings->reading_value);
        
        if (!is_null($meterReadings)) {
            $newValue = $meterReadings->reading_value;
            $lastMeterReadings = Index_Value::where('meter_id', $customer->mID)
            ->whereMonth("reading_date", $previousMonth)
            ->whereYear("reading_date", $previousYear)
            ->select("reading_value")
            ->first();
            if (!is_null($lastMeterReadings)) {
                $lastValue = $lastMeterReadings->reading_value;
            } else {
                $lastValue = 0;
            }
            $correctValue = $newValue-$lastValue;
        } else {
            $this->jobException("Error when meter reading."); // temporary
        }
        $contractProduct = Contract_product::join('products as p', 'p.id', '=', 'contract_products.product_id')
        ->where('customer_contract_id', '=', $customer->ccID)
        ->where('meter_id', '=', $customer->mID)
        ->whereNull('contract_products.end_date')
        ->select('contract_products.id as cpID', 'contract_products.start_date as cpStartDate', 'p.product_name as productName',
        'p.id as pID')
        ->first();
        // dd($contractProduct);

        $productTariff = Product::join('product_tariffs as pt', 'pt.product_id', '=', 'products.id')
        ->join('tariffs as t', 't.id', '=', 'pt.id')
        ->where('products.id', '=', $contractProduct->pID)
        ->whereNull('pt.end_date')
        ->first();
        // dd($productTariff);
        $invoiceData = [
            'invoice_date' => $invoiceDate->format('Y-m-d'),
            'due_date' => $invoiceDueDate->format('Y-m-d'),
            'total_amount' => 0,
            'status' => 'sent',
            'customer_contract_id' => $customer->ccID,
            'meter_id' => $customer->mID,
            'type' => 'Smart'
        ];
        // dd($invoiceData);
        // make invoice
        $invoice = Invoice::create($invoiceData);
        $lastInserted = $invoice->id;
        // service?
        $scService = new StructuredCommunicationService;
        $strCom = $scService->generate($lastInserted);
        $scService->addStructuredCommunication($strCom, $lastInserted);
        // discount
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
        // dd($discount);
        if (!is_null($discount)) {
            if(!is_null($discount->end_date)){
                $startDate = Carbon::create($discount->start_date);
                $endDate = Carbon::create($discount->end_date);

                $totalDays = $invoiceDate->subWeeks(2)->diffInDays($invoiceDueDate);
                $days = $startDate->diffInDays($endDate);

                // ex. 2 months = 31 - 60 = neg
                // ex. 2 weeks = 31 - 14 = pos

                if ($days > $totalDays && $endDate->month == $this->month) { //Overlapse into another month
                    $prevMonthDays = $startDate->copy()->endOfMonth()->day;
                    $thisMonthDays = $endDate->copy()->endOfMonth()->day;
                    
                    $days -= $prevMonthDays;
                    $discountRatio = $thisMonthDays - $days;
                } else {
                    $discountRatio = $totalDays - $days; 
                }

                if($discountRatio > 0) { //For # days of billing period

                    $discountPerDay = ($correctValue * $discount->rate) / $totalDays;
                    $discountAmount = $discountPerDay * $days;

                    $consumedPerDay = ($correctValue * $productTariff->rate) / $totalDays;
                    $consumedAmount = $consumedPerDay * $discountRatio;

                    $totalAmount = $discountAmount + $consumedAmount + 20;

                    $conumationPerDay = $correctValue / $totalDays;

                    Invoice_line::create([
                        'type' => "Electricity (for " . $discountRatio . "days)",
                        'unit_price' => $productTariff->rate,
                        'amount' => $conumationPerDay * $discountRatio,
                        'consumption_id' => null,
                        'invoice_id' => $lastInserted
                    ]);

                    Invoice_line::create([
                        'type' => "Electricity (discount for " . $days . " days)",
                        'unit_price' => $discount->rate,
                        'amount' => $conumationPerDay * $days,
                        'consumption_id' => null,
                        'invoice_id' => $lastInserted
                    ]);
                } else { //For whole billing period
                    $discountAmount = $correctValue * $discount->rate;
                    $totalAmount = $discountAmount + 20;

                    Invoice_line::create([
                        'type' => 'Electricity (discount)',
                        'unit_price' => $discount->rate,
                        'amount' => $correctValue,
                        'consumption_id' => null,
                        'invoice_id' => $lastInserted
                    ]);
                }        
            } else {
                $discountAmount = $correctValue * $discount->rate;
                $totalAmount = $discountAmount + 20;
                
                Invoice_line::create([
                    'type' => 'Electricity (discount)',
                    'unit_price' => $discount->rate,
                    'amount' => $correctValue,
                    'consumption_id' => null,
                    'invoice_id' => $lastInserted
                ]);
            }
        } else {
            $consumedAmount = $correctValue * $productTariff->rate;
            $totalAmount = $consumedAmount + 20;           //Transport & distribution costs    

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $productTariff->rate,
                'amount' => $correctValue,
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

        // standard invoice lines
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
        $this->sendSmartMail($invoice, $customer->uID, $newInvoiceLines);
    }

    public function sendSmartMail(Invoice $invoice, $uID, $newInvoiceLines)
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
        $this->sendMailInBackgroundWithPDF("ToCustomer@mail.com", SmartInvoiceMail::class, $mailParams, 'Invoices.smart_invoice_pdf', $pdfData, $invoice->id);

    }
}
?>