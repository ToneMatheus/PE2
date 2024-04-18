<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;

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
}
