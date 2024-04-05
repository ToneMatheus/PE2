<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Invoice_line;

class PaymentController extends Controller
{
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('Invoices.payment', compact('invoice'));
    }

    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Invoice successfully paid.');
    }
}
