<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class confirmationMailRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //$name so I can check the mailaddress
    public function __construct(protected $id, protected $email, protected $origin)
    // public function __construct(protected $user)
    { }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: 'Account Registration',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        if ($this->origin == "register"){
            $url = route('email-confirmation-registration', ['token' => $this->id, 'email' => $this->email]);
            // dd("in if");
            // dd($url);
        }else{
            $url = route('activate.account', ['encryptedUserID' => $this->id, 'email' => $this->email]);
        }
        
        // dd($url);

        return new Content(
            view: 'mails.accoutnMailConfirmation',
            with: [
                'url' => $url,
            ]
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
}
