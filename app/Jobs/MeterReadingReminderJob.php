<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeterReadingReminder;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Traits\jobLoggerTrait;

class MeterReadingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, jobLoggerTrait;

    protected $customeruID;
    protected $customermID;

    public function __construct($customeruID, $customermID)
    {
        $this->customeruID = $customeruID;
        $this->customermID = $customermID;
    }

    public function handle()
    {
        try {
            $this->jobStart();

            $user = User::join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
                ->join('addresses as a', 'a.id', '=', 'ca.address_id')
                ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
                ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
                ->where('users.id', '=', $this->customeruID)
                ->where('m.id', '=',  $this->customermID)
                ->first();

            if ($user) {
                Mail::to('shaunypersy10@gmail.com')->send(new MeterReadingReminder($user));
                $this->jobCompletion("Mail sent for user with ID: {$user->id}");
            } else {
                Log::error('User not found for MeterReadingReminderJob');
                $this->jobException('User not found for MeterReadingReminderJob');
            }
        } catch (\Exception $e) {
            Log::error("Error occurred while processing MeterReadingReminderJob: {$e->getMessage()}");
            $this->jobException("Error occurred while processing MeterReadingReminderJob: {$e->getMessage()}");
        }
    }
}

