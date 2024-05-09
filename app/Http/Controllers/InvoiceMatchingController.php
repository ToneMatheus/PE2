<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class InvoiceMatchingController extends Controller
{
    public function startMatching()
    {
        // get all payments from database in descending order
        $payments = Payment::select('*')->orderByDesc('payment_date')->get();

        //return all payments
        return view('invoices.InvoiceMatching', ['payments' => $payments]);
    }
}
