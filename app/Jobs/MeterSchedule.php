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
use Illuminate\Support\Facades\Log;


class MeterSchedule implements ShouldQueue
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
        $now = Carbon::now()->startOfDay();
        $newMeters = DB::table('meters')->select('id', 'installation_date')->get();

        foreach ($newMeters as $newMeter)
        {
            $install = Carbon::parse($newMeter->installation_date)->startOfDay();
            $diff = $install->floatDiffInYears($now);
            Log::info('diff: ',['diff' => $diff]);

            if ($diff > 1 && $diff % 3 == 0) {
                Log::info('diff: ',['calc diff' => $diff % 3]);
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
