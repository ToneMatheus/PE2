<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerPortalController extends Controller
{
    public function invoiceView(Request $request, $customerContractId)
    {
        $search = $request->get('search');
        $query = Invoice::query();
    
        if ($search) {
            $query->where('customer_contract_id', $customerContractId)
                  ->where(function ($query) use ($search) {
                      $query->where('id', $search)
                            ->orWhere('total_amount', $search)
                            ->orWhere('invoice_date', $search)
                            ->orWhere('due_date', $search)
                            ->orWhere('status', $search)
                            ->orWhere('type', $search);
                  });
        } else {
            $query->where('customer_contract_id', $customerContractId);
        }
    
        $invoices = $query->paginate(10);
        return view('Customers/CustomerInvoiceView', ['invoices' => $invoices, 'customerContractId' => $customerContractId]);
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
}
