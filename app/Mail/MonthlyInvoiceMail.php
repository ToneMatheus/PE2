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

    protected $invoice;
    protected $user;
    protected $estimation;
    protected $newInvoiceLines;
    protected $pdfData;

    public function __construct(Invoice $invoice, $user, $pdfData, $newInvoiceLines)
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
                        'newInvoiceLines' => $this->newInvoiceLines
                    ])
                    ->attachData($pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    private function generatePdf()
    {
        $pdf = Pdf::loadView('Invoices.monthly_invoice_pdf', [
            'invoice' => $this->invoice,
            'user' => $this->user,
            'newInvoiceLines' => $this->newInvoiceLines,
        ], [], 'utf-8');
        
             
        return $pdf->output();
    }
}
