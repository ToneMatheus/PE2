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
        Auth::loginUsingId(1);

        $search = $request->get('search');
        $status = $request->get('status');
        $query = Invoice::query();

        $user = auth()->user();
    
        if ($search) {
            $query->where('customer_contract_id', $user->id)
                  ->where(function ($query) use ($search) {
                      $query->where('id', $search)
                            ->orWhere('total_amount', $search)
                            ->orWhere('invoice_date', $search)
                            ->orWhere('due_date', $search)
                            ->orWhere('status', $search)
                            ->orWhere('type', $search);
                  });
        } else {
            $query->where('customer_contract_id', $user->id);
        }

        $sentInvoicesSum = Invoice::where('customer_contract_id', $user->id)
                          ->where('status', 'sent')
                          ->sum('total_amount');

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy('invoice_date', 'desc');
        $invoices = $query->paginate(10);
        return view('Customers/CustomerInvoiceView', compact('invoices', 'sentInvoicesSum'));
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
}
