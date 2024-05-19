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
use App\Http\Controllers\MeterController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderEnterIndexCustomerMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;



class EnterIndexCustomerJob implements ShouldQueue
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
                $user = DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('addresses','customer_addresses.id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=', 'meters.id')
                        ->where('meters.id', '=', $meter->id)
                        ->select('users.id', 'users.first_name', 'users.last_name')
                        ->get()
                        ->toArray();

                        $meter = Meter::find($meter->id);
                        $meter->expecting_reading = 1;
                        $meter->save();

                $userID = $user[0]->id; // Example user ID
                Log::info("userid = ", ['userID' => $userID]);

                $a = 5897;
                $b = 95471;
                $c = 42353;
                $tempUserID = (($userID * $a) / $b) + $c;
                Log::info("tempuserID = ", ['tempuserID' => $tempUserID]);

                $encryptedTempUserId = Crypt::encrypt($tempUserID);
                Log::info("tempuserID = ", ['enc' => $encryptedTempUserId]);

                Mail::to('anu01872@gmail.com')->send(new ReminderEnterIndexCustomerMail($user, $encryptedTempUserId));
            }
        }
    }
}
