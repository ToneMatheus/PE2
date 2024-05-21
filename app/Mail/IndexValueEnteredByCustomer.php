<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class IndexValueEnteredByCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //$name so I can check the mailaddress
    public function __construct(
        public $domain,
        public $user_id,
        public $EAN,
        public $index_value,
        public $date,
        public $consumption
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
            subject: 'Index value entered!',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        $a = 5897;
        $b = 95471;
        $c = 42353;
        $tempUserID = (($this->user_id * $a) / $b) + $c;
        Log::info("tempuserID = ", ['tempuserID' => $tempUserID]);

        $encryptedTempUserId = Crypt::encrypt($tempUserID);
        Log::info("tempuserID = ", ['enc' => $encryptedTempUserId]);

        return $this->view('mails.IndexValueEnteredByCustomer')
                    ->with([
                        'domain' => $this->domain, 
                        'user_id' => $this->user_id,
                        'token'=> $encryptedTempUserId,
                        'EAN' => $this->EAN,
                        'index_value' => $this->index_value,
                        'date' => $this->date,
                        'consumption' => $this->consumption
                    ]);
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
}
