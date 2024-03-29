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
    // public function __construct(protected $id)
    public function __construct(protected $user)
    {
        //
        // dd($this->request);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            /* It is possible to allow a GLOBAL email to be used. This is useful if we only use 1 email to send out all emails. 
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
            */
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
        // session()->flash('email', $this->user->email);

        // Session::put('id', $this->user->id);
        // Session::put('username', $this->user->username);
        // Session::put('first_name', $this->user->first_name);
        // Session::put('last_name', $this->user->last_name);
        // Session::put('password', $this->user->password);
        // Session::put('employee_profile_id', $this->user->employee_profile_id);
        // Session::put('is_company', $this->user->is_company);
        // Session::put('company_name', $this->user->company_name);
        // Session::put('email', $this->user->email);
        // Session::put('phone_nbr', $this->user->phone_nbr);
        // Session::put('birth_date', $this->user->birth_date);
        // Session::put('is_activate', $this->user->is_activate);

        Session::put('id', Crypt::encrypt($this->user->id));
        Session::put('username', Crypt::encrypt($this->user->username));
        Session::put('first_name', Crypt::encrypt($this->user->first_name));
        Session::put('last_name', Crypt::encrypt($this->user->last_name));
        Session::put('password', Crypt::encrypt($this->user->password));
        Session::put('employee_profile_id', Crypt::encrypt($this->user->employee_profile_id));
        Session::put('is_company', Crypt::encrypt($this->user->is_company));
        Session::put('company_name', Crypt::encrypt($this->user->company_name));
        Session::put('email', Crypt::encrypt($this->user->email));
        Session::put('phone_nbr', Crypt::encrypt($this->user->phone_nbr));
        Session::put('birth_date', Crypt::encrypt($this->user->birth_date));
        Session::put('is_activate', Crypt::encrypt($this->user->is_activate));


        $url = route('email-confirmation', ['token' => Crypt::encrypt($this->user->id),]);
        // $url = route('activate.account', ['encryptedUserID' => $this->id]);
        
        

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
