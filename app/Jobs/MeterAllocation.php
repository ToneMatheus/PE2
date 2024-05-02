<?php

namespace App\Jobs;

use App\Models\{
    User,
    Meter,
    Meter_Reader_Schedule,
    Customer_Address,
    Address,
    Consumption,
    Meter_Addresses,
};
use App\Traits\cronJobTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MeterAllocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($logLevel = null)
    {
        $this->LoggingLevel = $logLevel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now()->toDateString();
        $newMeters = Meter_Reader_Schedule::select('meter_id')
            ->whereDate('reading_date', '=', $now)
            ->get()
            ->pluck('meter_id')
            ->toArray();

        foreach ($newMeters as $newMeterID)
        {
            Meter_Reader_Schedule::where('meter_reader_schedules.employee_profile_id', 1000)
            ->where('meter_reader_schedules.meter_id', $newMeterID)
        ->update(['meter_reader_schedules.employee_profile_id' => 3]);
        }
    }
}
