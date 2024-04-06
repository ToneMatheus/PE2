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
                    // dd($customer);

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

                    // Find meter to check for index_values
                    $meters = Meter::join('meter_addresses as ma', 'ma.meter_id', '=', 'meters.id')
                    ->join('customer_addresses as ca', 'ca.address_id', '=', 'ma.address_id')
                    ->select('meters.id as meter_id')
                    ->where('ca.user_id', '=', $customer)
                    ->get()
                    ->pluck('meter_id')
                    ->toArray();

                    if (sizeof($meters) == 0) {
                        Log::error('Exception caught: ' . "Validation Error Code 1: No meter found for customer with id: $customer.");
                    } else {
                        foreach($meters as $meter) {
                            $consumptions = Index_Value::where('meter_id', '=', $meter)
                            ->whereyear('reading_date', '=', $year)
                            ->get()->toArray();
                            if (sizeof($consumptions) == 0) {
                                Log::error('Exception caught: ' . "Validation Error Code 2: No consumption found for meter with id: $meter.");
                            }
                            else {
                                Log::error("No validation error for meter with id: $meter.");
                            }
                        }
                    }
                    
                }
            }
        } catch (\Exception $code) {
            Log::error('Exception caught: ' . $code->getMessage());
        }
        

        // Validation process for all customers must start. What to validate. Consumption correct this month?
        
    }
}

