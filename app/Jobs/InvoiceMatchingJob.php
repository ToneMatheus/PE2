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
use App\Models\Address;
use App\Models\Customer_Address;

class InvoiceMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels, cronJobTrait;

    public $connection = 'sync';

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
                    // 4) match on addres
                    $this->matchOnAddress($payment);
                }
            }
        }
    }

    private function matchOnStructuredCommunication($payment)
    {
        if (is_null($payment->structured_communication))
            return false;

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
        if (is_null($payment->IBAN))
            return false;

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

    private function matchOnAddress($payment)
    {
        if (empty($payment->address))
            return false;

        $parts = explode(" ", $payment->address);
        $parts[1] = (int)$parts[1];

        $query = Address::query();
        $query->where('street', 'LIKE', $parts[0])
            ->where('number', '=', $parts[1]);

        if (count($parts) == 4) // no box in address
        {
            $parts[2] = (int)$parts[2];
            $query->where('postal_code', '=', $parts[2])
                ->where('city', 'LIKE', $parts[3]);
        }
        else if (count($parts) == 5)
        {
            $parts[2] = (int)$parts[2];
            $parts[3] = (int)$parts[3];
            $query->where('box', '=', $parts[2])
                ->where('postal_code', '=', $parts[3])
                ->where('city', 'LIKE', $parts[4]);
        }

        $address = $query->get()->first();

        if (empty($address)) //no address found
            return false;
        else
        {
            //find userID
            $userIDs = Customer_Address::select('user_id')
                ->where('address_id', '=', $address->id)
                ->whereNull('end_date')
                ->distinct()
                ->get()
                ->pluck('user_id')
                ->toArray();

            if (count($userIDs) == 0) //no user found
                return false;

            foreach ($userIDs as $userID)
            {
                //find invoice
                $invoiceRecord = Invoice::leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
                ->where('customer_contracts.user_id', $userID)
                ->where('invoices.total_amount', $payment->amount)
                ->whereNotIn('invoices.status', ['paid', 'pending'])
                ->first();

                if (!is_null($invoiceRecord)) //a matching invoice found
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
            
            return false; //no matching invoices found
        }
    }

    private function setPaid($invoiceID)
    {
        $invoice = Invoice::find($invoiceID);
        $invoice->status = 'paid';
        $invoice->save();
    }
}
