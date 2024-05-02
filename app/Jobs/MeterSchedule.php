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
        $now = Carbon::now()->startOfDay();

        $newMeters = DB::table('meters')->select('id', 'installation_date')->get();

        foreach ($newMeters as $newMeter)
        {
            $install = Carbon::parse($newMeter->installation_date)->startOfDay();
            $diff = $install->diffInYears($now);

            if ($diff > 0 && $diff % 3 == 0) {
                DB::table('meter_reader_schedules')->insert(
                ['employee_profile_id' => 1000,
                'meter_id' => $newMeter->id,
                'reading_date' => $now,
                'status' => 'unread',
                ]);
            }
        }
    }
}