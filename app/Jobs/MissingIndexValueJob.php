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
use Illuminate\Support\Facades\Crypt;
use App\Mail\MissingOneWeekMail;
use App\Mail\MissingTwoWeeksMail;
use App\Traits\jobLoggerTrait;

class MissingIndexValueJob implements ShouldQueue
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
            $diff = $install->floatDiffInYears($now);
            Log::info('diff: ',['meter_id'=>$newMeter->id, 'diff' => fmod($diff, 3)]);

            if ($diff > 1 && (fmod($diff, 3) > 0 && fmod($diff, 3) < 0.05)) {
                $temp_date =  Carbon::parse($newMeter->installation_date)->addYears(round($diff));
                $diffWeek = $temp_date->floatDiffInWeeks($now);
                Log::info('diff: ',['diffweek' => $diffWeek]);

                if ($diffWeek == 1) {
                    Log::info('diff: ',['added meter' => $newMeter->id, 'diffweek' => $diffWeek]);
                    $user = DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('addresses','customer_addresses.address_id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->where('meters.id', '=', $newMeter->id)
                        ->select('users.id', 'users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.city', 'addresses.postal_code')
                        ->first();

                    $a = 5897;
                    $b = 95471;
                    $c = 42353;
                    $tempUserID = (($user->id * $a) / $b) + $c;
                    $encryptedTempUserId = Crypt::encrypt($tempUserID);

                    Mail::to(config('app.email'))->send(new MissingOneWeekMail(config('app.host_domain'), $user, $encryptedTempUserId, 50));
                }
                elseif ($diffWeek == 2) {
                    Log::info('diff: ',['added meter' => $newMeter->id, 'diffweek' => $diffWeek]);

                    $user = DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('addresses','customer_addresses.address_id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->where('meters.id', '=', $newMeter->id)
                        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.city', 'addresses.postal_code')
                        ->first();
                    
                        Mail::to(config('app.email'))->send(new MissingTwoWeeksMail($user, 50));

                    DB::table('meter_reader_schedules')->insert(
                    ['employee_profile_id' => 1000,
                    'meter_id' => $newMeter->id,
                    'reading_date' => $now,
                    'status' => 'unread',
                    'priority' => 1
                    ]);
                }
            }
        }
    }
}
