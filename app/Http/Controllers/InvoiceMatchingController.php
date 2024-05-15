<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Jobs\InvoiceMatchingJob;

class InvoiceMatchingController extends Controller
{
    public function startMatching()
    {
        InvoiceMatchingJob::dispatch();
        // get all payments from database in descending order
        $payments = Payment::select('*')->orderByDesc('payment_date')->get();

        //return all payments
        return view('invoices.InvoiceMatching', ['payments' => $payments]);
    }

    public function filter(Request $request)
    {
        $query = Payment::query();

        if ($request->input('date')) 
        {
            $query->whereDate('payment_date', $request->input('date'));
        }

        if ($request->input('name')) 
        {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->input('iban')) 
        {
            $query->where('IBAN', 'LIKE', '%' . $request->input('iban') . '%');
        }

        if ($request->input('unmatched')) 
        {
            $query->where('has_matched', 0);
        }

        $payments = $query->orderByDesc('payment_date')->get();

        return view('invoices.InvoiceMatching', ['payments' => $payments]);
    }

}
