<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlyInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $domain;
    protected $invoice;
    protected $user;
    protected $estimation;
    protected $newInvoiceLines;
    protected $pdfData;

    public function __construct($pdfData, Invoice $invoice, $user, $newInvoiceLines)
    {
        $this->invoice = $invoice;
        $this->user = $user;
        $this->newInvoiceLines = $newInvoiceLines;
        $this->pdfData = $pdfData;
        $this->domain = config('app.host_domain');
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: 'Invoice Mail',
        );
    }

    public function build()
    {
        return $this->view('Invoices.invoice_mail')
                    ->with([
                        'user' => $this->user,
                        'invoice' => $this->invoice,
                        'newInvoiceLines' => $this->newInvoiceLines,
                    ])
                    ->attachData($this->pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
