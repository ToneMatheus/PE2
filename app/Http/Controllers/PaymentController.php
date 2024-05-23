<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
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
                $user = Invoice::select('users.first_name', 'users.last_name')
                ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
                ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
                ->where('invoices.id', $id)
                ->first();
                return view('Invoices.payment', compact('invoice', 'user'));
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
        $rules = [
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'address' => ['nullable', 'regex:/^\w+\s+\d+\s+(?:[A-Za-z0-9]+\s+)?\d*\s*\w+\s+\w+$/']
        ];

        $messages = [
            'amount.required' => 'Fill in the paid amount.',
            'payment_date.required' => 'Fill in the date of the payment.',
            'address.regex' => 'The address format must be "Street Number [Box] PostalCode City".',
        ];

        $request->validate($rules, $messages);

        Payment::create($request->all());

        return redirect()->route('payment.create')->with('success', 'Payment successfully added.');
    }
}
