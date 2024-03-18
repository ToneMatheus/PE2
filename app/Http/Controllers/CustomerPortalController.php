<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function showConsumptionHistory()
    {
        $consumptionData = DB::table('index_values')
            ->where('meter_id',1) //Auth::id())
            ->orderBy('reading_date')
            ->get();

        return view('Customers/CustomerConsumptionHistory', ['consumptionData' => $consumptionData]);
    }
}
