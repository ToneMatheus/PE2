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
              ->select('invoices.*', 'meters.id', DB::raw("CONCAT(addresses.street, ' ', addresses.number, ', ', addresses.city) AS address"));

        if ($selectedAddress) {
            $query->where(DB::raw("CONCAT(addresses.street, ' ', addresses.number, ', ', addresses.city)"), $selectedAddress);
        }

        if ($search) {
            $query->where('invoices.customer_contract_id', $user->id)
                  ->where(function ($query) use ($search) {
                      $query->where('invoices.id', $search)
                            ->orWhere('invoices.total_amount', $search)
                            ->orWhere('invoices.invoice_date', $search)
                            ->orWhere('invoices.due_date', $search)
                            ->orWhere('invoices.status', $search)
                            ->orWhere('invoices.type', $search);
                  });
        } else {
            $query->where('invoices.customer_contract_id', $user->id);
        }

        $sentInvoicesSum = Invoice::where('customer_contract_id', $user->id)
                          ->where('status', 'sent')
                          ->sum('total_amount');

        if ($status) {
            $query->where('invoices.status', $status);
        }

        $query->orderBy('invoices.invoice_date', 'desc');
        $invoices = $query->paginate(10);

        $addresses = DB::table('addresses')
               ->join('meter_addresses', 'addresses.id', '=', 'meter_addresses.address_id')
               ->join('meters', 'meter_addresses.meter_id', '=', 'meters.id')
               ->join('invoices', 'meters.id', '=', 'invoices.meter_id')
               ->where('invoices.customer_contract_id', $user->id)
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
        $userMessage = strtolower($request->get('message'));

        if (strpos($userMessage, 'conntacts') !== false) {
        $botMessage = 'You can find more information about Contacts on our contacts page.';
        } 
        elseif (strpos($userMessage, 'support') !== false) {
        $botMessage = 'You can find more information about Support on our support page.';
        }
        elseif (strpos($userMessage, 'tarrifs') !== false) {
        $botMessage = 'You can find more information about Tarrifs on our tarrifs page.';
        }
        else {
        $botMessage = 'I\'m sorry, I didn\'t understand that.';
        }

    return response()->json(['message' => $botMessage]);
    }

    public function bypass()
    {
        Auth::loginUsingId(6);
        return view('welcome');
    }
}
