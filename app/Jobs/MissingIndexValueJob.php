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
use Illuminate\Support\Facades\Mail;
use App\Mail\MissingMeterReading;
use Illuminate\Support\Facades\Log;
use App\Traits\jobLoggerTrait;

class MissingMeterValueJob implements ShouldQueue
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

        $meters = DB::table('meters')->select('id', 'installation_date')->get();

        foreach ($meters as $meter)
        {
            $install = Carbon::parse($meter->installation_date)->startOfDay();
            $diff = $install->diffInYears($now);

            if ($diff > 0 && $diff % 3 <= 2) {
                $userID = DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('addresses','customer_addresses.id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=', 'meters.id')
                        ->where('meters.id', '=', $meter->id)
                        ->select('users.id', 'users.first_name')
                        ->get()
                        ->toArray();

                Mail::to('shresthaanshu555@gmail.com')->send(new MissingMeterReading($userID[0]->id));
            }
        }
    }
}
