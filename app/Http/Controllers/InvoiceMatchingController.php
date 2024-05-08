<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Customer_contracts;

class InvoiceMatchingController extends Controller
{
    //$payments, $invoice_id
    public function startMatching()
    {
        // 1) get all payments from database
        $payments = Payment::all();

        // 2) match on struc comm
        foreach ($payments as $payment)
        {
            if (!$this->matchOnStructuredCommunication($payment))
            {
                // 3) match on IBAN
                if (!$this->matchOnIBAN($payment))
                {
                    //match on address?
                }
            }
        }

        //return all payments
        return view('invoices.InvoiceMatching', ['payments' => $payments]);
    }

    private function matchOnStructuredCommunication($payment)
    {
        $invoiceID = Invoice::where('structured_communication', '=', $payment->structured_communication)->first('id');

        if (is_null($invoiceID))
            return false;
        else
        {
            $editPayment = Payment::find($payment->id);
            $editPayment->has_matched = 1;
            $editPayment->invoice_id = $invoiceID;
            $editPayment->save();
            return true;
        }
    }

    private function matchOnIBAN($payment)
    {
        $userID = User::where('IBAN', '=', $payment->IBAN)->first('id');

        if (empty($userIDs)) //no users found with this IBAN
            return false;
        else
        {
            //find invoice
            $invoiceID = Invoice::leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->where('customer_contracts.user_id', $userID)
            ->where('invoices.total_amount', $payment->amount)
            ->whereNotIn('invoices.status', ['paid', 'pending'])
            ->first('id');

            if (is_null($invoiceID)) //no matching invoices found for this user
                return false;

            $editPayment = Payment::find($payment->id);
            $editPayment->has_matched = 1;
            $editPayment->invoice_id = $invoiceID;
            $editPayment->save();
            return true;
        }
    }
}
