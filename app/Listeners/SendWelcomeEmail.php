<?php

namespace App\Listeners;

use App\Events\NewEmployeeRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewEmployeeWelcomeMail;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail
{
    use InteractsWithQueue;

    public function handle(NewEmployeeRegistered $event)
    {
        $newEmployee = $event->employee;

        //personal mail
        Mail::to('shaunypersy10@gmail.com')->send(new NewEmployeeWelcomeMail($newEmployee));
    }
}
