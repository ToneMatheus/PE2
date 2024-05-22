<?php

namespace App\Jobs;

use App\Models\Estimation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Meter;
use App\Models\Index_Value;
use App\Traits\cronJobTrait;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

                WeekAdvanceReminderJob::dispatch();
                InvoiceFinalWarningJob::dispatch();

                $meters = Meter::whereTypeAndStatus("Electricity", "Installed")->where("is_smart", "=", 0)
                ->get();
                // dd($meters);
                if(is_null($meters) || count($meters) == 0){
                    $this->logWarning(null, "Database Error Code 1: No installed manual meters of type electricity found."); 
                }else {
                    foreach($meters as $meter){
                        // dd($meter); 
                        $customers = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
                        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
                        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
                        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
                        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
                        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
                        ->where("m.id", "=", $meter['id'])
                        ->whereNull("cc.end_date")
                        ->first();

                       

                        if(is_null($customers)){
                            // Check what error it is in the customer array.
                            $customers2 = User::join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
                            ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
                            ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
                            ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
                            ->select('users.id as uID', 'm.id as mID' )
                            ->where("m.id", "=", $meter['id'])
                            ->first();
                            if(is_null($customers2)) {
                                $meter_id = $meter['id'];
                                $this->logError(null, "Customer array is null for meter with id: $meter_id. Meter does not have a customer.");
                                Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                            } else {
                                $meter_id = $meter['id'];
                                $this->logError(null, "Customer array is null for meter with id: $meter_id. The customer tied to this meter does not have an active contract.");
                                Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                            }
                        } else {
                            $startContract = Carbon::parse($customers['startContract']);
                            // dd($customers);
                            // dd($startContract);
                            $lastYearlyInvoice = Invoice::where('type', '=', 'Annual')
                            ->where('meter_id', '=', $meter['id'])
                            ->orderBy('invoice_date', 'desc')
                            ->first();
                            // dd($lastYearlyInvoice);
                            if(is_null($lastYearlyInvoice)){
                                $invoiceCount = Invoice::where('meter_id', '=', $customers['mID'])
                                ->where('invoice_date', '>=', $customers['startContract'])
                                ->where('invoice_date', '<=', $now)
                                ->count();
                                // dd($invoiceCount);
                            } else {
                                $invoiceCount = Invoice::where('meter_id', '=', $customers['mID'])
                                ->where('invoice_date', '>', $lastYearlyInvoice['invoice_date'])
                                ->where('invoice_date', '<=', $now)
                                ->count();
                                // dd($invoiceCount);
                            }
                            
                            if($invoiceCount < 11){
                                //Monthly checks
                                if(sizeof(Estimation::get()->where('meter_id', '=', $meter['id'])->toArray()) == 0){
                                    // did not find the estimation
                                    $meter_id = $meter['id'];
                                    $this->logError(null, 'Exception caught: ' . "Validation Error Code 1: No monthly estimation found for meter with id: $meter_id");
                                    Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                                }elseif(Estimation::select('estimation_total')->where('meter_id', '=', $meter['id'])->pluck('estimation_total')->toArray()[0] <= 0){
                                    // estimation is 0 of lager                  
                                    $meter_id = $meter['id'];
                                    $this->logError(null, 'Exception caught: ' . "Validation Error Code 2: Monthly estimation found to be 0 or lower for meter with id: $meter_id");
                                    Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                                }else{
                                    $meter_id = $meter['id'];
                                    // estimation gevonden en hoger dan 0
                                    $this->logInfo(null, "No validation error for meter with id: $meter_id.");
                                    Meter::where('id', $meter_id)->update(['has_validation_error' => 0]);
                                }
                            } else {
                                //Yearly checks
                                $meter_id = $meter['id'];

                                $contractDuration = $startContract->diffInYears($now);

                                $invoiceDate = $startContract->addYear()->addWeeks(2);
                                $lastInvoiceDate = ($contractDuration < 1) ? $startContract->start_date : $invoiceDate->copy()->setYear($year-1);
                
                                $invoiceDate->setYear($year);
                                $invoiceDate->setMonth($month);
                                $invoiceDate->setTimezone('Europe/Berlin');
                                
                                //Reminder index values 1 week prior invoice run
                                if($invoiceDate->copy()->subWeek() == $now){
                                    MeterReadingReminderJob::dispatch($customers->uID, $customers->mID);
                                }

                                $consumptions = Index_Value::where('meter_id', '=', $meter_id)
                                ->where('reading_date', '>=', $lastInvoiceDate)
                                ->where('reading_date', '<', $invoiceDate->copy()->addWeek())
                                ->get()->toArray();

                                if (sizeof($consumptions) == 0) {
                                    // no consumption found
                                    $this->logError(null, 'Exception caught: ' . "Validation Error Code 3: No consumption data found for meter with id: $meter_id.");
                                    if($invoiceDate->copy() == $now){
                                        MissingMeterReadingJob::dispatch($customers->uID, $customers->mID);
                                    }

                                    Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                                }
                                else {
                                    // consumption found
                                    $this->logInfo(null, "No validation error for meter with id: $meter_id.");
                                    Meter::where('id', $meter_id)->update(['has_validation_error' => 0]);
                                }
                            }
                        }
                    }
                }
                $meters = Meter::whereTypeAndStatus("Electricity", "Installed")->where("is_smart", "=", 1)
                ->get();
                // dd($meters);
                if(is_null($meters) || count($meters) == 0){
                    $this->logWarning(null, "Database Error Code 1: No installed smart meters of type electricity found.");  
                }else {
                    foreach($meters as $meter){
                        // dd($meter); 
                        $customers = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
                        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
                        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
                        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
                        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
                        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
                        ->where("m.id", "=", $meter['id'])
                        ->whereNull("cc.end_date")
                        ->first();

                        if(is_null($customers)){
                            // Check what error it is in the customer array.
                            $customers2 = User::join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
                            ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
                            ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
                            ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
                            ->select('users.id as uID', 'm.id as mID' )
                            ->where("m.id", "=", $meter['id'])
                            ->first();

                            if(is_null($customers2)) {
                                $meter_id = $meter['id'];
                                $this->logError(null, "Customer array is null for meter with id: $meter_id. Meter does not have a customer.");
                                Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                            } else {
                                $meter_id = $meter['id'];
                                $this->logError(null, "Customer array is null for meter with id: $meter_id. The customer tied to this meter does not have an active contract.");
                                Meter::where('id', $meter_id)->update(['has_validation_error' => 1]);
                            }
                        } else {
                            //smart meter checks
                            $meter_id = $meter['id'];
                            $consumptions = Index_Value::where('meter_id', '=', $meter_id)
                            ->whereyear('reading_date', '=', $year)
                            ->get()->toArray();

                            if (sizeof($consumptions) == 0) {
                                // no consumption found
                                Log::error('Exception caught: ' . "Validation Error Code 3: No consumption data found for meter with id: $meter_id.");
                                Invoice::where('id', '=', $invoice_id)->update(['status' => 'validation error 3']);
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

