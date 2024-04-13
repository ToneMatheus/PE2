<?php

namespace App\Jobs;

use App\Mail\InvoiceFinalWarning;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Models\Invoice;

class InvoiceFinalWarningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $now;

    public function __construct()
    {
        $this->now = config('app.now');
    }

    public function handle()
    {
        //Query which invoices have not been paid yet and were due 1 week ago
        try
        {

            $unpaidInvoices = Invoice::select('id')
                ->whereNotIn('status', ['paid', 'pending'])
                ->whereDate('due_date', '=',  $this->now->subDays(7)->toDateString())
                ->get()
                ->pluck('id')
                ->toArray();
        }
        catch(\Exception $e)
        {
            Log::error("Unable to execute query to find unpaid invoices: " . $e);
        }

        //For each ID, send mail
        if (!empty($unpaidInvoices))
        {
            foreach ($unpaidInvoices as $unpaidInvoice)
            {
                $this->sendMail($unpaidInvoice);
            }
        }
        else
        {
            Log::info("No unpaid invoices found: no reminders were sent.");
        }
    }

    public function sendMail(int $invoiceID)
    {
        //gather data of users: name, e-mail
        //gather data of lines of invoice
        try
        {
            $invoice = new Invoice();
            $invoice = Invoice::find($invoiceID);
            $invoice_info = $invoice->invoice_lines;

            $total_amount = Invoice::where('id', $invoiceID)->value('total_amount');

            $user_info = Invoice::select('users.email', 'users.first_name', 'users.last_name')
                ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
                ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
                ->where('invoices.id', $invoiceID)
                ->first();
        }
        catch(\Exception $e)
        {
            Log::error("Unable to retrieve invoice information from invoice with ID ".$invoiceID.": " . $e);
        }

        if (Mail::to('shaunypersy10@gmail.com')->send(new InvoiceFinalWarning($invoice_info, $total_amount, $user_info)) == null)
        {
            Log::error("Unable to send unpaid invoice final warning mail for invoice with ID ". $invoiceID);
        }
    }
}
