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

    protected $domain;
    protected $user;
    protected $encryptedTempUserId;

    public function __construct($domain, $user, $encryptedTempUserId)
    {
        $this->domain = $domain;
        $this->user = $user;
        $this->encryptedTempUserId = $encryptedTempUserId;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: config('app.now')->format("m-d") . ' Missing meter index values',
        );
    }

    public function build()
    {
        return $this->view('Invoices.missing_meter_reading')
                    ->with([
                        'domain' => $this->domain,
                        'user' => $this->user,
                        'encryptedTempUserId' => $this->encryptedTempUserId]);
    }

    public function attachments()
    {
        return [];
    }
}
