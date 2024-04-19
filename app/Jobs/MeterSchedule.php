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

class MeterSchedule implements ShouldQueue
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
        $date = Carbon::now()->subYears(3)->toDateString();
        $newMeters = Meter::select('id')
            ->whereDate('installation_date', '=', $date)
            ->get()
            ->pluck('id')
            ->toArray();

        foreach ($newMeters as $newMeterID)
        {
            DB::table('meter_reader_schedules')->insert(
                ['employee_profile_id' => 1,
                'meter_id' => $newMeterID,
                'reading_date' => $now,
                'status' => 'unread',
                ]
            );
        }
    }
}
