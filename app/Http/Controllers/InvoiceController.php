<?php

namespace App\Http\Controllers;
use App\Mail\meter_reading_notice;
use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Mail\InvoiceMail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    //

   
    public function store(Request $request)
    {
        //Validate required paramters
        $request->validate([
            'type' => 'required',
            'price' => 'required',
            'status' => 'required',
            'due_date' => 'required',
        ]);
        //Aquire the current month
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('y');

        // Query for users that started a contract this month of the year
        $customers = DB::table('Customers')
            ->join('CustomerContract', 'Customers.id', '=', 'CustomerContract.customerID')
            ->whereMonth('CustomerContract.startDate', '=', $month)
            ->select('Customers.*')
            ->get();
        // then we have to check whether we have their meter readings
        $customersWithReadings = DB::table('Customers')
            ->join('CustomerAddress', 'Customers.id', '=', 'CustomerAddress.CustomerID')
            ->join('Address', 'CustomerAddress.AddressID', '=', 'Address.id')
            ->join('AddressMeter', 'Address.id', '=', 'AddressMeter.AddressID')
            ->join('Meter', 'AddressMeter.MeterID', '=', 'Meter.id')
            ->join('Index', 'Meter.id', '=', 'Index.MeterID')
            ->whereYear('Index.ReadingDate', '=', $year)
            ->select('Customers.*')
            ->get();
        // if we dont have their meter readings we have to aquire them by sending out a notice to the user to or by sending out an employee 

        // To aquire all customers that have a contract started in the current month but without meter readings we can just simply compare the 2 collections
        $customersNoReadings = $customers->diff($customersWithReadings);
        foreach ($customersNoReadings as $customer) {
            //If we do not have the meter readings and the last 3 meter readings were done by the user, we send out an employee to go and take the readings
            $meterReadingsSchedule = DB::table('Customers')
            ->join('CustomerAddress', 'Customers.id', '=', 'CustomerAddress.CustomerID')
            ->join('Address', 'CustomerAddress.AddressID', '=', 'Address.id')
            ->join('AddressMeter', 'Address.id', '=', 'AddressMeter.AddressID')
            ->join('Meter', 'AddressMeter.MeterID', '=', 'Meter.id')
            ->join('MeterReaderSchedule', 'Meter.id', '=', 'MeterReaderSchedule.MeterID')
            ->where('MeterReaderSchedule.Date', '>=', $year-3)
            ->where('Customers.id', '=', $customer->id)
            ->select('MeterReaderSchedule.*')
            ->get();
            if($meterReadingsSchedule == null){
                //We did not find a schedule in the last 3 years, which means we have to send out an employee to take the meter readings
                //#TODO Send an employee to the meter
            }else{
                //The customer is allowed to send their meter readings through the customer portal
                //since it has not been 3 years since an employee took readings
                //TODO:  This email should be changed for the customer email
                Mail::to('finnvc99@gmail.com')->send(new meter_reading_notice($customer));
            }

        }
        //Now the customers without meter readings have been dealt with, we can make invoices for the customers we have readings from
        foreach($customersWithReadings as $customer){
            $consumptions = DB::table('Customers')
            ->join('CustomerAddress', 'Customers.id', '=', 'CustomerAddress.CustomerID')
            ->join('Address', 'CustomerAddress.AddressID', '=', 'Address.id')
            ->join('AddressMeter', 'Address.id', '=', 'AddressMeter.AddressID')
            ->join('Meter', 'AddressMeter.MeterID', '=', 'Meter.id')
            ->join('Index', 'Meter.id', '=', 'Index.MeterID')
            ->join('Consumption', 'Index.id', '=', 'Cunsumption.CurrentIndexID')
            ->whereYear('Index.ReadingDate', '=', $year)
            ->where('Customers.id', '=', $customer->id)
            ->select('Consumption.*')
            ->get();
            $invoice = Invoice::create();
            $lastInserted = $invoice->id;
            foreach($consumptions as $consumption){
                Invoice_line::create($consumption, $lastInserted);
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
        $pdf = Pdf::loadView('Invoices.invoice_pdf', compact('invoice'));
        return $pdf->download('invoice.pdf');
    }
    public function showAddInvoiceExtraForm(Request $request)
    {
        $user = $request->input('id');
        return view('Invoices.AddInvoiceExtra', ['userID' => $user]);
    }
    public function AddInvoiceExtra(Request $request)
    {
        $type = $request->input('type');
        $amount = $request->input('amount');
        $userID = $request->input('userID');
        $amount = floatval($amount);
        DB::table('credit_notes')->insert(array(
            'type' => $type,
            'amount' => $amount,
            'user_id' => $userID,
            'is_credit' => 0,
            'is_active' => 1));
        $users = DB::table('users as u')
        ->join('customer_contracts as cc', 'cc.user_id', '=', 'u.id')
        ->get();
        return view('Invoices.TestUserList', compact('users'));
    }
    //test
    public function showTestUserList()
    {
        $users = DB::table('users as u')
        ->join('customer_contracts as cc', 'cc.user_id', '=', 'u.id')
        ->get();
        return view('Invoices.TestUserList', compact('users'));
    }
    public function showTestEmployeeList()
    {
        return view('Invoices.CustomerInvoicesOverview');
    }

    public function showAllInvoices(Request $request){

        // This fetch is dumb as it gets all invoices just to show a dynamic year filter
        // But i cant rely on the invoices that go through the query as that may have filters on there
        // $invoices = Invoice::all();
        $invoices = Invoice::select('invoice_date')
                   ->groupBy('invoice_date')
                   ->get()->toArray();
        // dd($invoices);
        $filterYears = [];
            foreach($invoices as $invoice){
                $invoiceYearMonth = date('Y-M', strtotime($invoice['invoice_date']));
                if (!in_array($invoiceYearMonth, $filterYears)){
                    array_push($filterYears, $invoiceYearMonth);
                }
            }        

        $invoicesQuery = Invoice::query()
            ->join('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->join('users', 'customer_contracts.user_id', '=', 'users.id')
            ->select('invoices.id', 'invoices.meter_id', 'users.first_name', 'users.last_name', 'invoices.invoice_date', 'invoices.due_date', 'invoices.status')->orderby('invoices.id');
        

        // Filter by time range
        $selectedYear = "";
        if ($request->has('year') && $request->input('year') != '') {
            $selectedYear = $request->input('year');
            list($selectedYear, $selectedMonth) = explode('-', $selectedYear);
            $selectedMonth = (int)date('m', strtotime($selectedMonth));
            $invoicesQuery->whereYear('invoice_date', $selectedYear);
            $invoicesQuery->whereMonth('invoice_date', $selectedMonth);
        }

        // Filter by payment status
        $selectedStatus = "";
        if ($request->has('status') && $request->input('status') != '') {
            $selectedStatus = $request->input('status');
            if ($selectedStatus === 'paid') {
                $invoicesQuery->where('status', 'paid');
            } elseif ($selectedStatus === 'unpaid') {
                $invoicesQuery->where('status', 'unpaid');
            } elseif ($selectedStatus === 'sent') {
                $invoicesQuery->where('status', 'sent');
            }
        }

        $invoices = $invoicesQuery->get();
        // dd($invoices);
        return view('Invoices/EmployeeInvoicesOverview', compact('invoices', 'filterYears', 'selectedYear', 'selectedStatus'));
    }
}
