<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use App\Mail\weekAdvanceReminder;

class advancemailcontroller extends Controller
{
    public function index()
    {
            //Query which invoices have not been paid yet and are due in 1 week
        $unpaidInvoices = Invoice::select('id')
        ->whereNotIn('status', ['paid', 'pending'])
        ->whereDate('due_date', '=', now()->addDays(7)->toDateString())
        ->get()
        ->pluck('id')
        ->toArray();

        //For each ID, send mail
        if (!empty($unpaidInvoices))
        {
            foreach ($unpaidInvoices as $unpaidInvoice)
            {
                $this->sendMail($unpaidInvoice);
            }
        }
    }

    public function sendMail(int $invoiceID)
    {
        //gather data of users: name, e-mail
        //gather data of lines of invoice
        $invoice = new Invoice();
        $invoice = Invoice::find($invoiceID);
        $invoice_info = $invoice->invoice_lines;

        $total_amount = Invoice::where('id', $invoiceID)->value('total_amount');

        $user_info = Invoice::select('users.email', 'users.first_name', 'users.last_name')
            ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
            ->where('invoices.id', $invoiceID)
            ->first();

        Mail::to('niki.de.visscher@gmail.com')->send(new weekAdvanceReminder($invoice_info, $total_amount, $user_info));
    }
}
