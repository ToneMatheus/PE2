<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Customer_contracts;

use Illuminate\Support\Facades\Log;

class InvoiceMatchingController extends Controller
{
    public function startMatching()
    {
        // 1) get all payments from database
        $payments = Payment::select('*')->orderByDesc('payment_date')->get();

        // 2) match on structured communication
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
        $invoiceRecord = Invoice::where('structured_communication', '=', $payment->structured_communication)->first();

        if (is_null($invoiceRecord))
            return false;
        else
        {
            $invoiceID = $invoiceRecord->id;
            $editPayment = Payment::find($payment->id);
            $editPayment->has_matched = 1;
            $editPayment->invoice_id = $invoiceID;
            $editPayment->save();
            $this->setPaid($invoiceID);
            return true;
        }
    }

    private function matchOnIBAN($payment)
    {
        Log::info('in function');
        $userRecord = User::where('IBAN', '=', $payment->IBAN)->first();

        if (empty($userRecord)) //no users found with this IBAN
            return false;
        else
        {
            //find invoice
            $userID = $userRecord->id;
            $invoiceRecord = Invoice::leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->where('customer_contracts.user_id', $userID)
            ->where('invoices.total_amount', $payment->amount)
            ->whereNotIn('invoices.status', ['paid', 'pending'])
            ->first();

            if (is_null($invoiceRecord)) //no matching invoices found for this user
                return false;

            $invoiceID = $invoiceRecord->id;
            $editPayment = Payment::find($payment->id);
            $editPayment->has_matched = 1;
            $editPayment->invoice_id = $invoiceID;
            $editPayment->save();
            $this->setPaid($invoiceID);
            return true;
        }
    }

    private function setPaid($invoiceID)
    {
        $invoice = Invoice::find($invoiceID);
        $invoice->status = 'paid';
        $invoice->save();
    }
}
