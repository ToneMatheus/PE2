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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        $i = 1;
        foreach ($newMeters as $newMeterID)
        {
            $i++;
            Meter_Reader_Schedule::where('meter_reader_schedules.employee_profile_id', 1)
            ->where('meter_reader_schedules.meter_id', $newMeterID)
        ->update(['meter_reader_schedules.employee_profile_id' => $i]);
        }
    }
}
