<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class weekAdvanceReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $invoice_info;
    public $total_amount;

    //temporary: make global variables?
    public $greeting = "Hello test";
    public $companyname = "energy supply business";

    public function __construct()
    {
        //place queries elsewhere and pass customer data to mailable
        $this->invoice_info = DB::select('SELECT * FROM invoice_lines il
        LEFT JOIN invoices i
        ON il.invoice_id = i.id
        LEFT JOIN customer_contracts cc
        ON i.customer_contract_id = cc.id
        WHERE cc.user_id = 1;'); //to change: id 1 for testing

        $this->total_amount = DB::select('SELECT i.total_amount FROM invoices i
        LEFT JOIN customer_contracts cc
        ON i.customer_contract_id = cc.id
        WHERE cc.user_id = 1;'); //to change: id 1 for testing
    }

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
