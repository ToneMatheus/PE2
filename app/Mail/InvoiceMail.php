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

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice;
    protected $name;

    public function __construct(Invoice $invoice, $name)
    {
        $this->invoice = $invoice;
        $this->name = $name;
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
                        'name' => $this->name,
                        'invoiceType' => $this->invoice->type,
                        'invoiceTotalAmount' => $this->invoice->total_amount,
                        'invoiceStatus' => $this->invoice->status,
                        'invoiceDueDate' => $this->invoice->due_date,
                    ])
                    ->attachData($pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    private function generatePdf()
    {
        $pdf = Pdf::loadView('Invoices.invoice_pdf', ['invoice' => $this->invoice]);
        return $pdf->output();
    }
}
