<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\Payment;

class PaymentController extends Controller
{
    public function show($id, $hash)
    {
        try
        {
            $invoice = Invoice::findOrFail($id);
            if ($hash == md5($invoice->id . $invoice->customer_contract_id . $invoice->meter_id))
            {
                return view('Invoices.payment', compact('invoice'));
            }
            else
            {
                return redirect()->route('dashboard');
            }
        }
        catch(Exception $e)
        {
            Log::info("Attempt to access illegal page for invoice payment.");
        }
    }

    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Invoice successfully paid.');
    }

    public function create()
    {
        return view('Invoices.add_payment');
    }

    public function add(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'IBAN' => 'required|string',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'structured_communication' => 'required|string'
        ]);

        Payment::create($request->all());

        return redirect()->route('payment.create')->with('success', 'Payment successfully added.');
    }
}
