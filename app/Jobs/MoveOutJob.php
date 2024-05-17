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

class MoveOutJob implements ShouldQueue
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

        $contracts = DB::table('customer_contracts')->select('start_date', 'end_date', 'user_id')->get();

        foreach ($contracts as $contract)
        {
            $start_date = Carbon::parse($contract->start_date)->startOfDay();
            $tenant_out = '';
            $tenant_in = '';

            if ($contract->end_date != null){
                $end_date = Carbon::parse($contract->end_date)->startOfDay();
                $diff = $end_date->diffInDays($now);
                if ($diff % 14 == 0) {
                    $tenant_out = $contract->user_id;
    
                    $out_meters = DB::table('users')
                    ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                    ->join('addresses','customer_addresses.id','=','addresses.id')
                    ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                    ->join('meters','meter_addresses.meter_id','=','meters.id')
                    ->where('users.id', '=', $tenant_out)
                    ->select('meters.id')
                    ->get();
    
                    foreach($out_meters as $out_meter)
                    DB::table('meter_reader_schedules')->insert(
                        ['employee_profile_id' => 1000,
                        'meter_id' => $out_meter->id,
                        'reading_date' => $end_date,
                        'status' => 'unread',
                        'priority' => 1
                        ]);
                }
            }
        }
    }
}
