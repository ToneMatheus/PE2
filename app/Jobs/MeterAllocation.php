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
        
        $total_meters = DB::table('meter_reader_schedules')
                        ->where('employee_profile_id', 1000)
                        ->count();

        $total_employees = DB::table('users')
                            ->whereNotNull('employee_profile_id')
                            ->where('employee_profile_id', '!=', 1000)
                            ->count();

        $meters_per_employee = floor($total_meters / $total_employees);
        $remaining_meters = $total_meters % $total_employees;

        $metersToBeAllocated = DB::table('meter_reader_schedules')
                                ->where('employee_profile_id', 1000)
                                ->pluck('meter_id')
                                ->toArray();

        $employee_ids = DB::table('users')
                        ->whereNotNull('employee_profile_id')
                        ->where('employee_profile_id', '!=', 1000)
                        ->pluck('employee_profile_id')
                        ->toArray();

        $slice_position = 0; // position from where assigned_meters array is sliced

        foreach($employee_ids as $employee_id) {
            $assigned_meters_count = $meters_per_employee + ($remaining_meters > 0 ? 1 : 0);

            $assigned_meters = array_slice($metersToBeAllocated, $slice_position, $assigned_meters_count);

            foreach ($assigned_meters as $meter_id) {
                $query = 'UPDATE meter_reader_schedules SET employee_profile_id = '.$employee_id.' WHERE meter_id = '. $meter_id;
                DB::update($query);
            }

            $slice_position += $assigned_meters_count;
            $remaining_meters--;
        }
    }
}
