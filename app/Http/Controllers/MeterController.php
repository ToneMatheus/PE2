<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Meter,
    Meter_Reader_Schedule,
    Customer_Address,
    Address,
    Meter_Addresses,
};
use App\Models\MeterReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use app\http\Controllers\CustomerController;
use illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Http\Response;

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

    public function viewScheduledMeters(Request $request) { // viewing meters for specific employee
        $results = DB::table('users')
                    ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                    ->join('addresses','customer_addresses.id','=','addresses.id')
                    ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                    ->join('meters','meter_addresses.meter_id','=','meters.id')
                    ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                    ->where('meter_reader_schedules.reading_date','=','2024-03-21')
                    ->where('meter_reader_schedules.employee_profile_id','=',1)
                    ->where('meter_reader_schedules.status','=','unread')
                    ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN')
                    ->get();
                    
        $employeeName = DB::table('users')
                        ->where('users.employee_profile_id', '=', 1)
                        ->select('users.first_name')
                        ->get();
        return view("Meters/employeeDashboard",['results'=>$results, 'employeeName'=>$employeeName]);
    }

    public function all_meters_index() {
        $employees = DB::table('users as u')
                        ->whereNotNull('u.employee_profile_id')
                        ->select('u.first_name', 'u.employee_profile_id as employee_id')
                        ->get();

        return view('Meters/all_meters_dashboard',['employees'=>$employees]);
    }

    public function search(Request $request) {
        if($request->ajax())
        {
            $output = '';
            $queryName = $request->get('queryName');
            $queryCity = $request->get('queryCity');
            $queryStreet = $request->get('queryStreet');
            $queryNumber = $request->get('queryNumber');
            $queryAssigned = $request->get('queryAssigned');

            if($queryName != '' || $queryCity != '' || $queryStreet != '' || $queryNumber != '' || $queryAssigned != '') { // getting all the required data for the table
                $query = DB::table('users')
                            ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                            ->join('addresses','customer_addresses.id','=','addresses.id')
                            ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                            ->join('meters','meter_addresses.meter_id','=','meters.id')
                            ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                            ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                            ->where('meter_reader_schedules.reading_date','=','2024-03-21')
                            ->where('meter_reader_schedules.status','=','unread')
                            ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.ID as meter_id', 'meter_reader_schedules.id', 'e.first_name as assigned_to')
                            ->orderBy('users.id');

                // searching with multiple parameters
                $query->where(function($query) use($queryName) {
                    $query->where('users.first_name','like','%'.$queryName.'%')
                        ->orWhere('users.last_name','like','%'.$queryName.'%');
                    })
                    ->where(function($query) use($queryCity) {
                        $query->where('addresses.city','like','%'.$queryCity.'%');
                    })
                    ->where(function($query) use($queryStreet) {
                        $query->where('addresses.street','like','%'.$queryStreet.'%');
                    })
                    ->where(function($query) use($queryNumber) {
                        $query->where('addresses.number','like','%'.$queryNumber.'%');
                    })
                    ->where(function($query) use($queryAssigned) {
                        $query->where('e.first_name','like','%'.$queryAssigned.'%')
                            ->orWhere('e.first_name','like','%'.$queryAssigned.'%');
                    });
            }
            else {
            $query = DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('addresses','customer_addresses.id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                        ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                        ->where('meter_reader_schedules.reading_date','=','2024-03-21')
                        ->where('meter_reader_schedules.status','=','unread')
                        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.ID as meter_id', 'meter_reader_schedules.id', 'e.first_name as assigned_to')
                        ->orderBy('users.id');
            }

            $employees = DB::table('users as u')
                        ->whereNotNull('u.employee_profile_id')
                        ->select('u.first_name', 'u.employee_profile_id as employee_id')
                        ->get();

            $data = $query->get();
             
            $total_row = $data->count();
            if($total_row > 0){
                $i = 1;
                foreach($data as $row)
                {
                    $output .= '
                    <tr>
                    <td>'.$i.'</td>
                    <td>'.$row->first_name.' '.$row->last_name.'</td>
                    <td>'.$row->street.' '.$row->number.', '.$row->city.'</td>
                    <td>'.$row->assigned_to.'</td>';

                    $output .= '
                    <td>
                        <form method="POST" action="/assignment_change">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type=\'hidden\' name=\'meter_id\' class="meter_id" value='.$row->meter_id.'>
                        <select name=\'assignment\' class=\'assignment\'>';

                    foreach($employees as $employee)
                    {
                        $output .= '<option value='.$employee->employee_id;
                        if ($row->assigned_to == $employee->first_name) {
                            $output .= ' selected';
                        }
                        else {
                            $output .= '';
                        }
                        
                        $output.= '>'.$employee->first_name.'</option>';
                    }

                    $output .= '</select>
                    <button type="submit">Apply changes</button>
                    </form>
                    </td>
                    ';

                    $i++;
                }
            } else {
                $output = '
                <tr>
                    <td align="center" colspan="5">No Data Found</td>
                </tr>
                ';
            }
            $data = array(
                'table_data'  => $output,
                'total_data'  => $total_row
            );
            echo json_encode($data);
        }
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

    public function enter_index_pageload() {
        return view('Meters/enterIndexEmployee');
    }

    public function searchIndex(Request $request)  {
        if($request->ajax())
        {
            $output = '';
            $queryName = $request->get('queryName');
            $queryEAN = $request->get('queryEAN');
            $queryCity = $request->get('queryCity');
            $queryStreet = $request->get('queryStreet');
            $queryNumber = $request->get('queryNumber');

            if($queryName != '' || $queryEAN != '' || $queryCity != '' || $queryStreet != '' || $queryNumber != '') { // getting all the required data for the table
                $query = DB::table('users')
                            ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                            ->join('addresses','customer_addresses.id','=','addresses.id')
                            ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                            ->join('meters','meter_addresses.meter_id','=','meters.id')
                            ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                            ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                            ->where('meter_reader_schedules.reading_date','=','2024-03-21')
                            ->where('meter_reader_schedules.employee_profile_id','=',1)
                            ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id', 'meter_reader_schedules.id', 'meter_reader_schedules.status', 'e.first_name as assigned_to')
                            ->orderBy('users.id');

                // searching with multiple parameters
                $query->where(function($query) use($queryName) {
                    $query->where('users.first_name','like','%'.$queryName.'%')
                        ->orWhere('users.last_name','like','%'.$queryName.'%');
                    })
                    ->where(function($query) use($queryEAN) {
                        $query->where('meters.EAN','like','%'.$queryEAN.'%');
                    })
                    ->where(function($query) use($queryCity) {
                        $query->where('addresses.city','like','%'.$queryCity.'%');
                    })
                    ->where(function($query) use($queryStreet) {
                        $query->where('addresses.street','like','%'.$queryStreet.'%');
                    })
                    ->where(function($query) use($queryNumber) {
                        $query->where('addresses.number','like','%'.$queryNumber.'%');
                    });
            }
            else {
            $query = DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('addresses','customer_addresses.id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                        ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                        ->where('meter_reader_schedules.reading_date','=','2024-03-21')
                        ->where('meter_reader_schedules.employee_profile_id','=',1)
                        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id', 'meter_reader_schedules.id', 'meter_reader_schedules.status', 'e.first_name as assigned_to')
                        ->orderBy('users.id');
            }

            $data = $query->get();
            $total_row = $data->count();
            if($total_row > 0){
                foreach($data as $row)
                {
                    $output .= '<div class="searchResult';
                    
                    if ($row->status == "read") {
                        $output .= ' readMeter">';
                    }

                    if ($row->status == "unread") {
                        $output .= '">';
                    }
                    $output .= '<div class="searchResultLeft">
                                <p>Name: <span class="highlighted">'.$row->first_name.' '.$row->last_name.'</span></p>
                                <p>EAN code: <span class="highlighted">'.$row->EAN.'</span></p>
                                <p>Type: <span class="highlighted">'.$row->type.'</span></p>
                                <p>Address: '.$row->street.' '.$row->number.', '.$row->city.'</span></p>
                            </div>
                            <div class="searchResultRight">
                                <p>Status:<br>
                                    <span style="font-size:30px;color:';
                                    if ($row->status == "unread") {
                                        $output .= 'red;font-weight:bold;">'.ucfirst($row->status).'</span></p>
                                        <p>
                                            <button type="button" class="modalOpener" value='.$row->meter_id.'>Add index value</button>
                                        </p>';
                                    }
                                    else {
                                        $output .= 'white;font-weight:bold;">'.ucfirst($row->status).'</span></p>';
                                    }
                                
                        $output .= '
                                </p>
                            </div>
                        </div>';
                }
            } else {
                $output = '
                <tr>
                    <td align="center" colspan="5">No Data Found</td>
                </tr>
                ';
            }
            $data = array(
                'table_data'  => $output,
                'total_data'  => $total_row
            );
            echo json_encode($data);
        }
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

    public function GasElectricty(){

        DB::insert('INSERT INTO consumptions (consumptions_value, prev_index_id, current_index_id) Values (?,?,?)');
    }

    public function customerId($customerId)
        {
            $customer = User::find($customerId);
            $meters = $customer->meters()->get();

            return view('meters', compact('customer', 'meters'));
        }

        public function storeIndexValue(Request $request, $meterId)
        {
            // Validate request data
            $validatedData = $request->validate([
                'index_value' => 'required|numeric',
            ]);

            // Find the meter
            $meter = Meter::findOrFail($meterId);

            // Store the index value
            $meter->index_values()->create([
                'value' => $validatedData['index_value'],
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Index value added successfully.');
        }

}
