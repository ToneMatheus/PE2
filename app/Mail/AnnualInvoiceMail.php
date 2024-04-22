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

class AnnualInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $domain = "http://127.0.0.1:8000"; //change later
    protected $invoice;
    protected $user;
    protected $consumption;
    protected $estimation;
    protected $newInvoiceLine;
    protected $pdfData;
    protected $meterReadings;
    protected $discounts;
    protected $monthlyInvoices;

    public function __construct(Invoice $invoice, $user, $pdfData, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices)
    {
        $this->invoice = $invoice;
        $this->user = $user;
        $this->consumption = $consumption;
        $this->estimation = $estimation;
        $this->newInvoiceLine = $newInvoiceLine;
        $this->pdfData = $pdfData;
        $this->meterReadings = $meterReadings;
        $this->discounts = $discounts;
        $this->monthlyInvoices = $monthlyInvoices;
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
                        'consumptions' => $this->consumption,
                        'estimation' => $this->estimation,
                        'newInvoiceLine' => $this->newInvoiceLine,
                        'meterReadings' => $this->meterReadings,
                        'discounts' => $this->discounts,
                        'monthlyInvoices' => $this->monthlyInvoices
                    ])
                    ->attachData($pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    private function generatePdf()
    {
        $hash = md5($this->invoice->id . $this->invoice->customer_contract_id . $this->invoice->meter_id);
        $pdf = Pdf::loadView('Invoices.annual_invoice_pdf', [
            'invoice' => $this->invoice,
            'user' => $this->user,
            'consumption' => $this->consumption,
            'estimation' => $this->estimation,
            'newInvoiceLine' => $this->newInvoiceLine,
            'meterReadings' => $this->meterReadings,
            'discounts' => $this->discounts,
            'monthlyInvoices' => $this->monthlyInvoices,
            'domain' => $this->domain,
            'hash' => $hash
        ], [], 'utf-8');
        
             
        return $pdf->output();
    }
}
