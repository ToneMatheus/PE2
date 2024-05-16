<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;


class CustomerPortalController extends Controller
{
    public function invoiceView(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $selectedAddress = $request->get('address');
        $query = Invoice::query();

        $user = auth()->user();

        $query->join('meters', 'invoices.meter_id', '=', 'meters.id')
              ->join('meter_addresses', 'meters.id', '=', 'meter_addresses.meter_id')
              ->join('addresses', 'meter_addresses.address_id', '=', 'addresses.id')
              ->join('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
              ->select('invoices.*', 'meters.id', DB::raw("CONCAT(addresses.street, ' ', addresses.number, ', ', addresses.city) AS address"))
              ->where('customer_contracts.user_id', $user->id);

        if ($selectedAddress) {
            $query->where(DB::raw("CONCAT(addresses.street, ' ', addresses.number, ', ', addresses.city)"), $selectedAddress);
        }

        if ($search) {
            $query->where(function ($query) use ($search) {
                      $query->where('invoices.id', $search)
                            ->orWhere('invoices.total_amount', $search)
                            ->orWhere('invoices.invoice_date', $search)
                            ->orWhere('invoices.due_date', $search)
                            ->orWhere('invoices.status', $search)
                            ->orWhere('invoices.type', $search);
                  });
        }


        if ($status) {
            $query->where('invoices.status', $status);
        }

        $query->orderBy('invoices.invoice_date', 'desc');
        $invoices = $query->paginate(10);

        foreach ($invoices as $invoice) {
            $invoice->hash = md5($invoice->id . $invoice->customer_contract_id . $invoice->meter_id);
        }

        $customerContractId = $user->customerContracts ? $user->customerContracts->first()->id : null;

        if ($customerContractId) {
            $sentInvoicesSum = Invoice::where('customer_contract_id', $customerContractId)
                              ->where('status', 'sent')
                              ->sum('total_amount');
        } else {
            $sentInvoicesSum = 0;
        }

        $addresses = DB::table('addresses')
               ->join('meter_addresses', 'addresses.id', '=', 'meter_addresses.address_id')
               ->join('meters', 'meter_addresses.meter_id', '=', 'meters.id')
               ->join('invoices', 'meters.id', '=', 'invoices.meter_id')
               ->join('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
               ->where('customer_contracts.user_id', $user->id)
               ->select(DB::raw("CONCAT(addresses.street, ' ', addresses.number, ', ', addresses.city) AS address"))
               ->distinct()
               ->get();

        return view('Customers/CustomerInvoiceView', compact('invoices', 'sentInvoicesSum', 'addresses'));
    }


    public function showConsumptionHistory($timeframe = 'month')
    {
        $query = DB::table('index_values')
            ->where('meter_id',1) //Auth::id())
            ->orderBy('reading_date');

            switch ($timeframe) {
                case 'week':
                    $query->whereDate('reading_date', '>=', Carbon::now()->startOfWeek())
                          ->whereDate('reading_date', '<=', Carbon::now()->endOfWeek());
                    break;
                case 'month':
                    $query->whereDate('reading_date', '>=', Carbon::now()->startOfMonth())
                          ->whereDate('reading_date', '<=', Carbon::now()->endOfMonth());
                    break;
                case 'year':
                    $query->whereDate('reading_date', '>=', Carbon::now()->startOfYear())
                          ->whereDate('reading_date', '<=', Carbon::now()->endOfYear());
                    break;
                case 'all':
                    break;
        }

        $consumptionData = $query->get();


        return response()->json(['consumptionData' => $consumptionData]);
    }

    public function showConsumptionPage()
    {
        $consumptionData = $this->showConsumptionHistory('month')->getData();
        return view('Customers/CustomerConsumptionHistory', ['consumptionData' => $consumptionData]);
    }

    public function changeLocale(Request $request)
    {
        session(['applocale' => $request->locale]);
        return back();
    }

    public function chatbot(Request $request)
    {
        $user = auth()->user();

        $firstName = $user->first_name; 
        $email = $user->email;

        return view('Customers/CustomerInvoiceView', compact('firstName', 'email'));
    }
}
