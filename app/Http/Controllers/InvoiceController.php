<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{

    public function showInvoices(Request $request){
        
        // This fetch is dumb as it gets all invoices just to show a dynamic year filter
        // But i cant rely on the invoices that go through the query as that may have filters on there
        $invoices = Invoice::all();
        $filterYears = [];
            foreach($invoices as $invoice){
                $invoiceYear = date('Y', strtotime($invoice['InvoiceDate']));
                if (!in_array($invoiceYear, $filterYears)){
                    array_push($filterYears, $invoiceYear);
                }
            }        
        
        $invoicesQuery = Invoice::query();

        // Filter by time range
        $selectedYear = "";
        if ($request->has('year') && $request->input('year') != '') {
            $selectedYear = $request->input('year');
            if ($selectedYear === 'last3Months') {
                $invoicesQuery->whereDate('invoiceDate', '>=', now()->subMonths(3));
            } elseif ($selectedYear === 'last6Months') {
                $invoicesQuery->whereDate('invoiceDate', '>=', now()->subMonths(6));
            } else {
                $invoicesQuery->whereYear('invoiceDate', $selectedYear);
            }
        }

        // Filter by payment status
        $selectedStatus = "";
        if ($request->has('status') && $request->input('status') != '') {
            $selectedStatus = $request->input('status');
            if ($selectedStatus === 'Paid') {
                $invoicesQuery->where('status', 'Paid');
            } elseif ($selectedStatus === 'Unpaid') {
                $invoicesQuery->where('status', 'Unpaid');
            }
        }

        $invoices = $invoicesQuery->get();

        return view('Invoices/CustomerInvoicesOverview', compact('invoices', 'filterYears', 'selectedYear', 'selectedStatus'));
    }
}
