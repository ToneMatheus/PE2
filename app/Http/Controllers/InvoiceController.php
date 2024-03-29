<?php

namespace App\Http\Controllers;
use App\Mail\meter_reading_notice;
use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Mail\InvoiceMail;
use App\Models\Meter_Reader_Schedule;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    //
    public function run()
    {
        $customersNoReadings = collect();   
        //Aquire the current date
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        //Hardcoded to have some data because most data is from januari
        $month = 01;
        // Query for users that started a contract this month of the year
        $customers = DB::table('users')
            ->join('customer_contracts', 'users.id', '=', 'customer_contracts.user_id')
            ->whereMonth('customer_contracts.start_date', '=', $month)
            ->select('users.*')
            ->get();
        
        // then we have to check whether we have their meter readings
        $customersWithReadings = DB::table('users')
            ->join('customer_addresses', 'users.id', '=', 'customer_addresses.user_id')
            ->join('addresses', 'customer_addresses.address_id', '=', 'addresses.id')
            ->join('meter_addresses', 'addresses.id', '=', 'meter_addresses.address_id')
            ->join('meters', 'meter_addresses.meter_id', '=', 'meters.id')
            ->join('index_values', 'meters.id', '=', 'index_values.meter_id')
            ->where('index_values.reading_date', '>=', $now->subYear(1))
            ->select('users.*')
            ->get();
        //To get a list of customers that requruire a yearly contract but without meter readings, we can compare the customers list and the customersWithReadings list
        foreach($customers as $customer){
            if(!$customersWithReadings->has($customer->id)){
                $customersNoReadings->push($customer);
            }
        }
        // if we dont have their meter readings we have to aquire them by sending out a notice to the user to or by sending out an employee 
        foreach ($customersNoReadings as $customer) {
            //If we do not have the meter readings and the last 3 meter readings were done by the user, we send out an employee to go and take the readings
            $meterReadingsSchedule = DB::table('users')
            ->join('customer_addresses', 'users.id', '=', 'customer_addresses.user_id')
            ->join('addresses', 'customer_addresses.address_id', '=', 'addresses.id')
            ->join('meter_addresses', 'addresses.id', '=', 'meter_addresses.address_id')
            ->join('meters', 'meter_addresses.meter_id', '=', 'meters.id')
            ->join('meter_reader_schedules', 'meters.id', '=', 'meter_reader_schedules.meter_id')
            ->whereYear('meter_reader_schedules.reading_date', '>', $now->subYear(3))
            ->where('users.id', '=', $customer->id)
            ->select('meter_reader_schedules.*')
            ->get();
            if($meterReadingsSchedule == null){
                //We did not find a schedule in the last 3 years, which means we have to send out an employee to take the meter readings
                //#TODO Send an employee to the meter
                Meter_Reader_Schedule::create();
            }else{
                //The customer is allowed to send their meter readings through the customer portal
                //since it has not been 3 years since an employee took readings
                //TODO:  This email should be changed for the customer email
                Mail::to('finnvc99@gmail.com')->send(new meter_reading_notice($customer));
            }

        }
        //Now the customers without meter readings have been dealt with, we can make invoices for the customers we have readings from
        foreach($customersWithReadings as $customer){
            //Acquire the consumptions of the customer
            $consumptions = DB::table('users')
            ->join('customer_addresses', 'users.id', '=', 'customer_addresses.user_id')
            ->join('addresses', 'customer_addresses.address_id', '=', 'addresses.id')
            ->join('meter_addresses', 'addresses.id', '=', 'meter_addresses.address_id')
            ->join('meters', 'meter_addresses.meter_id', '=', 'meters.id')
            ->join('index_values', 'meters.id', '=', 'index_values.meter_id')
            ->join('consumptions', 'index_values.id', '=', 'consumptions.current_index_id')
            ->whereYear('index_values.reading_date', '>=', $now->subYear(1))
            ->where('users.id', '=', $customer->id)
            ->select('consumptions.*', 'index_values.*')
            ->get();

            //Aquire the customer contract id
            $customer_contract_id = DB::table('users')
            ->join('customer_contracts', 'users.id', '=', 'customer_contracts.user_id')
            ->where('customer_contracts.user_id', '=', $customer->id)
            ->select('customer_contracts.id')
            ->get();

            $total_amount = 0;
            $invoice_model = [
                'invoice_date' => $now,
                'due_date' => $now->addDay(14),
                'total_amount' => $total_amount,
                'customer_contract_id' => $customer_contract_id,
                'type' => 'Yearly',
            ];

            $invoice = Invoice::create($invoice_model);

            //Making 2 base models for invoice lines for basic fees
            $service_fee_model = [
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 1,
                'consumption_id',
                'invoice_id' => $invoice->id,
            ];
            $distribution_fee_model = [
                'type' => 'Basic Distribution Fee',
                'unit_price' => 10,
                'amount' => 1,
                'consumption_id',
                'invoice_id' => $invoice->id,
            ];
            Invoice_line::create($service_fee_model);
            Invoice_line::create($distribution_fee_model);

            foreach($consumptions as $consumption){
                Invoice_line::create($consumption, $invoice->id);
            }
            
            $credit_notes = CreditNote::where('user_id', $customer->id);
            if($credit_notes != null){
                foreach($credit_notes as $note){
                    $creditNoteLines = $note->lines();
                    foreach($creditNoteLines as $line){
                        Invoice_line::create($line, $invoice->id);
                    }
                }

            }
            
        }

        return redirect()->route('invoice.index')
            ->with('success', 'Post created successfully.');
    }

    public function sendMail(Invoice $invoice)
    {
        $user = User::where('id', $invoice->user_id)->first();
        if ($invoice != null) {
            //finnvc99@gmail.com is going to be replaced with: $user->email
            Mail::to('finnvc99@gmail.com')->send(new InvoiceMail($invoice, $user->name));
        }
        return redirect()->intended('dashboard');
    }

    public function download(Request $request)
    {
        $invoice = Invoice::where('id', $request->id)->first();
        $user_id = DB::table('users')
        ->join('customer_contracts', 'users.id', '=', 'customer_contracts.user_id')
        ->join('invoices', 'customer_contracts.id','=', 'invoices.customer_contract_id')
        ->where('invoices.id', '=', $invoice->id)
        ->select('users.id')
        ->get();
        //Validate the logged in user is the actual data owner of the invoice data because it gets triggered via GET requests
        if(Auth::user()->id == $user_id){
            $pdf = Pdf::loadView('Invoices.invoice_pdf', compact('invoice'));
            return $pdf->download('invoice.pdf');
        }
        
    }

}
