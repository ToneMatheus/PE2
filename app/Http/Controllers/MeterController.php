<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;
use App\Models\Meter_Reader_Schedule;
use App\Models\MeterReading;
use Carbon\Carbon;
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
        WHERE meter_reader_schedules.reading_date = \'2024-03-21\' AND meter_reader_schedules.employee_profile_id = 1
        AND meter_reader_schedules.status = \'unread\';');

        $employeeName = DB::select('SELECT users.first_name FROM users WHERE users.employee_profile_id = 1;');

        return view("Meters/employeeDashboard",['results'=>$results, 'employeeName'=>$employeeName]);
    }

    public function viewAllMeters(Request $request) {
            $results = DB::select('SELECT u.id, u.first_name, u.last_name, addresses.street, addresses.number, addresses.postal_code, addresses.city, meters.EAN, meters.ID AS meter_id, meter_reader_schedules.id, e.first_name AS assigned_to FROM users as u
                RIGHT JOIN customer_addresses on u.id = customer_addresses.user_id
                RIGHT JOIN addresses on customer_addresses.address_id = addresses.id
                RIGHT JOIN meter_addresses on addresses.id = meter_addresses.address_id
                RIGHT JOIN meters on meter_addresses.meter_id = meters.id
                RIGHT JOIN meter_reader_schedules on meters.id = meter_reader_schedules.meter_id
                RIGHT JOIN users e on e.employee_profile_id = meter_reader_schedules.employee_profile_id
                WHERE meter_reader_schedules.reading_date = \'2024-03-21\'
                ORDER BY u.id;');

        $employees = DB::select('SELECT u.first_name, u.employee_profile_id AS employee_id FROM users as u
        WHERE u.employee_profile_id IS NOT NULL;');

        return view("Meters/all_meters_dashboard",['results'=>$results, 'employees'=>$employees]);
    }

    public function searchAllMeters(Request $request) {
            return $request;
    }

    public function assignment(Request $request) {
        $meter_id = $request->input('meter_id');
        $assignment = $request->input('assignment');
        $id = DB::select('SELECT id FROM meter_reader_schedules WHERE meter_id = ?', [$meter_id]);

        $data = Meter_Reader_Schedule::find($id[0]->id);
        $data->employee_profile_id = $assignment;
        $data->timestamps=false;
        $data->save();

        return redirect('/all_meters_dashboard');
    }

    public function enterIndex(Request $request) {
        $results = DB::select('SELECT id FROM meters;');
        return view("Meters/enterIndexEmployee",['results'=>$results]);
    }

    public function submitIndex(Request $request) {
        $date = Carbon::now()->toDateString();
        $meter_id = $request->input('meter_id');
        $index_value = $request->input('index_value');

        DB::insert('INSERT INTO index_values (reading_date, meter_id, reading_value) VALUES (?, ?, ?)', [$date, $meter_id, $index_value]);
        return redirect('enterIndexEmployee');
    }
}
