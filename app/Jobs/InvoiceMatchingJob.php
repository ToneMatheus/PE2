<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\cronJobTrait;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;

class InvoiceMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 1) get payments that haven't been matched yet
        $payments = Payment::select('*')->where('has_matched', 0)->get();

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
