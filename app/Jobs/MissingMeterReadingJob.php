<?php

namespace App\Jobs;

use App\Events\JobCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MissingMeterReading;
use App\Models\User;
use App\Models\Meter;
use App\Traits\cronJobTrait;
use Illuminate\Support\Facades\Log;
use App\Traits\jobLoggerTrait;

class MissingMeterReadingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    protected $customeruID;
    protected $customermID;

    public function __construct($jobRunId, $customeruID, $customermID, $logLevel = null)
    {
        $this->JobRunId = $jobRunId;
        $this->LoggingLevel = $logLevel;
        $this->customeruID = $customeruID;
        $this->customermID = $customermID;
    }

    public function handle()
    {
        try {
            $user = User::join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
                ->join('addresses as a', 'a.id', '=', 'ca.address_id')
                ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
                ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
                ->where('users.id', '=', $this->customeruID)
                ->where('m.id', '=',  $this->customermID)
                ->first();

            Meter::where('id', $this->customermID)->update(['expecting_reading' => 1]);

            if ($user) {
                $this->sendMailInBackground($user->email, MissingMeterReading::class, [$user]);
            } else {
                Log::error('User not found for MeterReadingReminderJob');
            }
        } catch (\Exception $e) {
            Log::error("Error occurred while processing MeterReadingReminderJob: {$e->getMessage()}");
        }
        event(new JobCompleted($this->JobRunId, $this->__getShortClassName()));
    }
}
