<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee_Profile;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NewEmployeeWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $employee;

    public function __construct(Employee_Profile $employee)
    {
        $this->employee = $employee;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('energysupplier@gmail.com', 'Energy Supplier'),
            subject: 'Welcome to the company',
        );
    }

    public function build()
    {
        return $this->view('mails.newEmployeeWelcome')
                    ->with([
                        'employee' => $this->employee,
                    ]);
    }
}