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

    protected $domain = "http://127.0.0.1:8000"; //change later
    protected $invoice;
    protected $user;
    protected $estimation;
    protected $newInvoiceLines;
    protected $pdfData;

    public function __construct(Invoice $invoice, $user, $newInvoiceLines, $pdfData)
    {
        $this->invoice = $invoice;
        $this->user = $user;
        $this->newInvoiceLines = $newInvoiceLines;
        $this->pdfData = $pdfData;
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
        $pdfData = $this->generatePdf();

        return $this->view('Invoices.invoice_mail')
                    ->with([
                        'user' => $this->user,
                        'invoice' => $this->invoice,
                        'newInvoiceLines' => $this->newInvoiceLines,
                    ])
                    ->attachData($pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    private function generatePdf()
    {
        $hash = md5($this->invoice->id . $this->invoice->customer_contract_id . $this->invoice->meter_id);
        $pdf = Pdf::loadView('Invoices.monthly_invoice_pdf', [
            'invoice' => $this->invoice,
            'user' => $this->user,
            'newInvoiceLines' => $this->newInvoiceLines,
            'domain' => $this->domain,
            'hash' => $hash
        ], [], 'utf-8');
        
             
        return $pdf->output();
    }
}
