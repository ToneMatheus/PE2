<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobDoneNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $jobName;

    public function __construct($jobName)
    {
        $this->jobName = $jobName;
    }

    public function build()
    {
        return $this->subject(config('app.now')->format("m-d") . ' Job Done Notification')
                    ->markdown('mails.job_done_notification');
    }
}
