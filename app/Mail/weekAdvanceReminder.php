<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
            from: 'energy-company@example.com',
            subject: 'Open Invoice: Advance Reminder',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails\advance-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments() //to add: invoice pdf in attachments
    {
        return [];
    }
}
