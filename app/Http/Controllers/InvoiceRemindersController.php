<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Invoice,
    Contract_product,
    Product,
    Invoice_line,
    CreditNote,
    Estimation,
    Index_Value,
    Discount
};
use App\Mail\InvoiceDue;
use App\Mail\MonthlyInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

//*** this controller is for testing purposes only ***

class InvoiceRemindersController extends Controller
{
    public function index()
    {
        //Query which invoices are due today
        $unpaidInvoices = Invoice::select('id')
        ->whereNotIn('status', ['paid', 'pending'])
        ->whereDate('due_date', '=', now()->toDateString())
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

        Mail::to('niki.de.visscher@gmail.com')->send(new InvoiceDue($invoice_info, $total_amount, $user_info));
    }

    public function monthly()
    {
        $invoiceData = [
            'invoice_date' => '2024-04-22',
            'due_date' => '2024-04-22',
            'total_amount' => 0,
            'status' => 'sent',
            'customer_contract_id' => 1,
            'meter_id' => 1,
            'type' => 'Monthly'
        ];

        $invoice = Invoice::create($invoiceData);
        $lastInserted = $invoice->id;

        $invoice->save();

        Invoice_line::create([
            'type' => 'Basic Service Fee',
            'unit_price' => 10.00,
            'amount' => 1,
            'consumption_id' => null,
            'invoice_id' => $lastInserted
        ]);

        Invoice_line::create([
            'type' => 'Distribution Fee',
            'unit_price' => 10.00,
            'amount' => 1,
            'consumption_id' => null,
            'invoice_id' => $lastInserted
        ]);

        $newInvoiceLines = Invoice_line::where('invoice_id', '=', $lastInserted)->get();

        $user = User::join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('a.is_billing_address', '=', 1)
        ->where('users.id', '=', 1)
        ->first();

        // Generate PDF
        $pdf = Pdf::loadView('Invoices.monthly_invoice_pdf', [
            'invoice' => $invoice,
            'user' => $user,
            'newInvoiceLines' => $newInvoiceLines,
            'domain' => 'http://127.0.0.1:8000'
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        //Send email with PDF attachment
        Mail::to('masteranime18@gmail.com')->send(new MonthlyInvoiceMail(
            $invoice, $user, $pdfData, $newInvoiceLines
        ));
    }
}
