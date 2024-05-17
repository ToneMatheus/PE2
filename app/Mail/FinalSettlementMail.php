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

class FinalSettlementMail extends Mailable
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
    protected $interval;

    public function __construct($pdfData, Invoice $invoice, $user, $consumption, $newInvoiceLine, $interval)
    {
        $this->invoice = $invoice;
        $this->user = $user;
        $this->consumption = $consumption;
        $this->newInvoiceLine = $newInvoiceLine;
        $this->pdfData = $pdfData;
        $this->interval = $interval;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: 'Invoice Mail: Final Settlement',
        );
    }

    public function build()
    {
        return $this->view('Invoices.final_settlement')
                    ->with([
                        'user' => $this->user,
                        'invoice' => $this->invoice,
                        'consumptions' => $this->consumption,
                        'newInvoiceLine' => $this->newInvoiceLine,
                        'interval' => $this->interval
                    ])
                    ->attachData($this->pdfData, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
