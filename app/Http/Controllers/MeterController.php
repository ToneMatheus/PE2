<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;
use App\Models\MeterReading;
use Illuminate\Support\Facades\DB;

class MeterController extends Controller
{
    //https://stackoverflow.com/a/19890444
    /*function generateEAN($number)
    {
    $code = '54' . str_pad($number, 15, '0', STR_PAD_LEFT);
    $weightflag = true;
    $sum = 0;
    for ($i = strlen($code) - 1; $i >= 0; $i--)
    {
        $sum += (int)$code[$i] * ($weightflag?3:1);
        $weightflag = !$weightflag;
    }
    $code .= (10 - ($sum % 10)) % 10;
    return $code;
    }*/

    public function showMeters()
    {
        return view('Meters/meters');
    }

    public function addMeters()
    {

        function generateEAN($number)
        {
        $number += 1;
        $code = '54' . str_pad($number, 15, '0', STR_PAD_LEFT);
        $weightflag = true;
        $sum = 0;
        for ($i = strlen($code) - 1; $i >= 0; $i--)
        {
            $sum += (int)$code[$i] * ($weightflag?3:1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - ($sum % 10)) % 10;
        return $code;




        
        }

        $latest = DB::table('meter')->latest('id')->first();
        Meter::create([
            'EAN' => generateEAN($latest->ID),
            'type' => request('type'),
            'installationDate' => request('installationDate'),
            'status' => request('status')
        ]);
        return redirect('/meters');
    }

    public function showMeterHistory()
    {
        return view('Meters/Meter_History');

    }

    public function showConsumptionReading()
    {
        return view('Meters/Consumption_Reading');
    }

    public function viewScheduledMeters(Request $request) {
        $results = DB::select('SELECT users.first_name, users.last_name, addresses.street, addresses.number, addresses.postal_code, addresses.city, meters.EAN FROM users
        JOIN customer_addresses on users.id = customer_addresses.user_id
        JOIN addresses on customer_addresses.address_id = addresses.id
        JOIN meter_addresses on addresses.id = meter_addresses.address_id
        JOIN meters on meter_addresses.meter_id = meters.id
        JOIN meter_reader_schedules on meters.id = meter_reader_schedules.meter_id
        WHERE meter_reader_schedules.reading_date = \'2024-03-21\';');

        return view("Meters/employeeDashboard",['results'=>$results]);
    }
}
