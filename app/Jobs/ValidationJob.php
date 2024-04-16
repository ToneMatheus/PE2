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
use App\Models\Meter;
use App\Models\Index_Value;

use App\Mail\MonthlyInvoiceMail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Services\InvoiceFineService;

class ValidationJob implements ShouldQueue
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
        
        try {
            // Check database connection
            DB::connection()->getPdo();
            if(!DB::connection()->getDatabaseName()){
                throw new \Exception("Database Error Code 0: No connection could be made");
            } else {
                // Get all users with customer contract.
                $customers = User::join('customer_contracts as cc', 'cc.user_id', '=', 'users.id')
                ->select('cc.user_id as user_id')
                ->where('users.is_active', '=', 1)
                ->get()->pluck('user_id')->toArray();
                // dd($customers);

                // Customer Validation
                if (sizeof($customers) == 0) {
                    throw new \Exception("Validation Error Code 0: No active customers found.");
                }
                foreach($customers as $customer){
                    $invoices = Invoice::join('customer_contracts as cc', 'cc.id', "=", 'invoices.customer_contract_id')
                    ->select('invoices.id as invoice_id', 'invoices.invoice_date', 'invoices.due_date', 'invoices.total_amount', 'invoices.status', 'invoices.customer_contract_id', 'invoices.type', 'invoices.meter_id', 'cc.user_id')
                    ->where('cc.user_id', '=', $customer)
                    ->whereYear('invoices.invoice_date', '=', $year)
                    ->whereMonth('invoices.invoice_date', '=', $month)
                    ->get()->toArray();
                    // dd($invoices);
                    foreach($invoices as $invoice){
                        // dd($invoice['type']);
                        if ($invoice['type'] == "Monthly" || $invoice['type'] == "monthly"){
                            // Estimation validation
                            // Can i find the estimation
                            if(sizeof(Estimation::get()->where('meter_id', '=', $invoice['meter_id'])->toArray()) == 0){
                                // did not find the estimation
                                $meter_id = $invoice['meter_id'];
                                $invoice_id = $invoice['invoice_id'];
                                Log::error('Exception caught: ' . "Validation Error Code 1: No monthly estimation found for meter with id: $meter_id");
                                Invoice::where('id', '=', $invoice_id)->update(['status' => 'validation error']);
                            }elseif(Estimation::select('estimation_total')->where('meter_id', '=', $invoice['meter_id'])->pluck('estimation_total')->toArray() <= 0){
                                // estimation is 0 of lager
                                $meter_id = $invoice['meter_id'];
                                $invoice_id = $invoice['invoice_id'];
                                Log::error('Exception caught: ' . "Validation Error Code 2: Monthly estimation found to be 0 or lower for meter with id: $meter_id");
                                Invoice::where('id', '=', $invoice_id)->update(['status' => 'validation error']);
                            }else{
                                $meter_id = $invoice['meter_id'];
                                $invoice_id = $invoice['invoice_id'];
                                // estimation gevonden en hoger dan 0
                                Log::error("No validation error for meter with id: $meter_id.");
                                Invoice::where('id', '=', $invoice_id)->update(['status' => 'validation ok']);
                            }
                        }elseif($invoice['type'] == "Yearly" || $invoice['type'] == "yearly" || $invoice['type'] == "Annual" || $invoice['type'] == "annual"){
                            // Consumption validation
                            $meter_id = $invoice['meter_id'];
                            $invoice_id = $invoice['invoice_id'];
                            $consumptions = Index_Value::where('meter_id', '=', $meter_id)
                            ->whereyear('reading_date', '=', $year)
                            ->get()->toArray();
                            if (sizeof($consumptions) == 0) {
                                // no consumption found
                                Log::error('Exception caught: ' . "Validation Error Code 3: No consumption found for meter with id: $meter_id.");
                                Invoice::where('id', '=', $invoice_id)->update(['status' => 'validation error']);
                            }
                            else {
                                // consumption found
                                Log::error("No validation error for meter with id: $meter_id.");
                                Invoice::where('id', '=', $invoice_id)->update(['status' => 'validation ok']);
                            }
                        }
                    }
                    // Make new invoices
                    // $invoiceData = [
                    //     'invoice_date' => Carbon::now()->toDateString(), //temporary
                    //     'due_date' => Carbon::now()->addWeeks(2)->toDateString(), //temporary
                    //     'total_amount' => 0, //temporary
                    //     'status' => 'pending',
                    //     'customer_contract_id' => $customer['id'],
                    //     'type' => 'None'
                    // ];
                    // $invoice = Invoice::create($invoiceData);
                    // dd($invoice);
                }
            }
        } catch (\Exception $code) {
            Log::error('Exception caught: ' . $code->getMessage());
        }
        

        // Validation process for all customers must start. What to validate. Consumption correct this month?
        
    }
}

