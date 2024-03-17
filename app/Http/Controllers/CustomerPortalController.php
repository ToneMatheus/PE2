<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class CustomerPortalController extends Controller
{
    public function invoiceView($customerContractId)
    {
        $invoices = Invoice::where('customer_contract_id', $customerContractId)->get();
        return view('Customers/CustomerInvoiceView', ['invoices' => $invoices]);
    }
}
