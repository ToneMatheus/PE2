<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class MissingMeterReading extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: 'Missing meter index values',
        );
    }

    public function build()
    {
        return $this->view('Invoices.missing_meter_reading')
                    ->with([
                        'user' => $this->user,]);
    }

    public function attachments()
    {
        return [];
    }
}
