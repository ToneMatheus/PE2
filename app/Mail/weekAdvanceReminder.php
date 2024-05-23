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
use App\Services\QRCodeService;
use Illuminate\Support\Facades\Crypt;

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
            subject: config('app.now')->format("M-d") . 'Open Invoice: Advance Reminder',
        );
    }

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
        $id = $this->encryptID($this->user_info->id);

        return $this->view('mails.advance-reminder')
                    ->with([
                        'user_info' => $this->user_info,
                        'invoice_info' => $this->invoice_info,
                        'total_amount' => $this->total_amount,
                        'id' => $id
                    ])
                    ->attachData($pdfData, 'QRcode.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    public function encryptID($userID)
    {
        $a = 5897;
        $b = 95471;
        $c = 42353;
        $tempUserID = (($userID * $a) / $b) + $c;

        return Crypt::encrypt($tempUserID);
    }
}
