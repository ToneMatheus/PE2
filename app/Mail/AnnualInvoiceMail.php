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
use Illuminate\Support\Facades\Log;

class AnnualInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $domain;
    protected $invoice;
    protected $user;
    protected $consumption;
    protected $estimation;
    protected $newInvoiceLine;
    protected $pdfData;
    protected $meterReadings;
    protected $discounts;
    protected $monthlyInvoices;

    public function __construct($pdfData, Invoice $invoice, $user, $consumption, $estimation, $newInvoiceLine, $meterReadings, $discounts, $monthlyInvoices)
    {
        $this->invoice = $invoice;
        $this->user = $user;
        $this->consumption = $consumption;
        $this->estimation = $estimation;
        $this->newInvoiceLine = $newInvoiceLine;
        $this->meterReadings = $meterReadings;
        $this->discounts = $discounts;
        $this->monthlyInvoices = $monthlyInvoices;
        $this->pdfData = $pdfData;
        $this->domain = config('app.host_domain');
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: config('app.now')->format("m-d") . ' Annual Invoice Mail',
        );
    }

    public function build()
    {
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
                    ->attachData($this->pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
