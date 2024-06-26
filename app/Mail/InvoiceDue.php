<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Services\QRCodeService;

class InvoiceDue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */    

     public $companyname = "energy supply business";
     public $fine = 50;
    
     public function __construct(    
        public $invoice_info,
        public $total_amount,
        public $user_info
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
            subject: config('app.now')->format("m-d") . ' Invoice Due',
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
            view: 'mails\invoice_due_mail',
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
        $QRCodeService = new QRCodeService();
        $pdfData = $QRCodeService->PaymentQRCodePdf($this->invoice_info[0]->invoice_id);

        return $this->view('mails.invoice_due_mail')
                    ->with([
                        'user_info' => $this->user_info,
                        'invoice_info' => $this->invoice_info,
                        'total_amount' => $this->total_amount
                    ])
                    ->attachData($pdfData, 'QRcode.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
