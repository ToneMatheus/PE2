<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class weekAdvanceReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    //temporary: make global variables?
    public $companyname = "energy supply business";
    public $domain = "http://127.0.0.1:8000";

    public $pdfData;

    public function __construct(    
        public $invoice_info,
        public $total_amount,
        public $user_info,
        )
    {}

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: 'Open Invoice: Advance Reminder',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    /*public function content()
    {
        return new Content(
            view: 'mails\advance-reminder',
        );
    }*/

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build()
    {
        $pdfData = $this->generatePdf();

        return $this->view('mails.advance-reminder')
                    ->with([
                        'user_info' => $this->user_info,
                        'invoice_info' => $this->invoice_info,
                        'total_amount' => $this->total_amount
                    ])
                    ->attachData($pdfData, 'QRcode.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    private function generatePdf()
    {
        $pdf = Pdf::loadView('Invoices.QRCode_pdf', [
            'domain' => $this->domain,
            'invoiceID' => $this->invoice_info[0]->invoice_id
        ], [], 'utf-8');
        return $pdf->output();
    }
}
