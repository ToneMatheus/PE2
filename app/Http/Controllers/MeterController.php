<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Http;
use app\http\Controllers\CustomerController;
use illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\IndexValueEnteredByCustomer;


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

    public function showConsumptionDashboard()
    {
        return view('Meters/Consumption_Dashboard');
    }

    public function viewScheduledMeters(Request $request) { // viewing meters for specific employee
        $today = Carbon::now()->toDateString();
        $company_address = urlencode('Jan Pieter de Nayerlaan 7 Sint Katelijne Waver');
        $key = env('GOOGLE_API_KEY');
        $addresses = [];
        $waypoints = '';
        $optimized_waypoints = '';

        $results = DB::table('users')
                    ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                    ->join('addresses','customer_addresses.id','=','addresses.id')
                    ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                    ->join('meters','meter_addresses.meter_id','=','meters.id')
                    ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                    ->where('meter_reader_schedules.reading_date','=', $today)
                    ->where('meter_reader_schedules.employee_profile_id','=',1)
                    ->where('meter_reader_schedules.status','=','unread')
                    ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.id', 'meters.type', 'meter_reader_schedules.priority')
                    ->get();

        $employeeName = DB::table('users')
                        ->where('users.employee_profile_id', '=', 1)
                        ->select('users.first_name')
                        ->get();

        foreach($results as $result) {
            $address = $result->street.' '.$result->number.' '.$result->city;
            $addresses[] = $address;
            $waypoints .= urlencode($address.'|');
        }

        $waypoints = substr($waypoints, 0, -3); // removing the pipe character in the end

        $api = 'https://maps.googleapis.com/maps/api/directions/json?destination=' . $company_address . '&origin=' . $company_address . '&waypoints=optimize%3Atrue%7C' . $waypoints . env('GOOGLE_API_KEY');
        $response = Http::get($api); // getting JSON response from the API

        $data = json_decode($response, true);
        $waypoint_order = $data['routes'][0]['waypoint_order']; // optimized order of addresses

        foreach($waypoint_order as $position) {
            $optimized_waypoints .= urlencode($addresses[$position]) . "|";
        }

        $optimized_waypoints = substr($optimized_waypoints, 0, -1); // removing the pipe character in the end

        $url = "https://www.google.com/maps/embed/v1/directions?origin=" . $company_address .
                "&destination=" . $company_address .
                "&waypoints=" . $optimized_waypoints . "&avoid=highways" . env('GOOGLE_API_KEY'); // creating url for maps embed

        return view("Meters/employeeDashboard",['results'=>$results, 'employeeName'=>$employeeName, 'url'=>$url]);
    }

    public function all_meters_index() {
        $employees = DB::table('users as u')
                        ->whereNotNull('u.employee_profile_id')
                        ->select('u.first_name', 'u.employee_profile_id as employee_id')
                        ->get();

        return view('Meters/all_meters_dashboard',['employees'=>$employees]);
    }

    public function search(Request $request) {
        $today = Carbon::now()->toDateString();
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
                            ->where('meter_reader_schedules.reading_date','=', $today)
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
                        ->where('meter_reader_schedules.reading_date','=', $today)
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

    public function bulk_assignment(Request $request) {
        $previous_employee = $request->input('previous_employee');
        $next_employee = $request->input('next_employee');

        Meter_Reader_Schedule::where('meter_reader_schedules.employee_profile_id', $previous_employee)
        ->update(['meter_reader_schedules.employee_profile_id' => $next_employee]);

        return redirect('/all_meters_dashboard');
    }

    public function searchIndex(Request $request)  {
        if($request->ajax())
        {
            $today = Carbon::now()->toDateString();
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
                            ->where('meter_reader_schedules.reading_date','=', $today)
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
                        ->where('meter_reader_schedules.reading_date','=', $today)
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

    public function submitIndex(Request $request) {
        $errors = new MessageBag();
        $request->validate([
            'meter_id' => 'required',
            'index_value' => 'required|integer'
        ],
        [
            'meter_id.required' => 'Meter ID inclusion failed for unknown reasons.',
            'index_value.required' => 'Please enter an index value!',
            'index_value.integer' => 'You have to type in a number for the index value.'
        ]);

        $date = Carbon::now()->toDateString();
        $meter_id = $request->input('meter_id');
        $index_value = $request->input('index_value');

        // $estimation = DB::table('estimations')
        // ->where('estimations.meter_id', '=', $meter_id)
        // ->select('estimations.estimation_total')
        // ->get();

        $prev_index = DB::table('index_values')
        ->join('consumptions', 'consumptions.current_index_id', '=', 'index_values.id')
        ->where('index_values.meter_id', '=', $meter_id)
        ->select('index_values.id', 'index_values.reading_value', 'index_values.reading_date')
        ->orderBy('consumptions.id', 'desc')
        ->get()
        ->first();

        if ($prev_index == null) {
            return redirect()->to('/enter_index_employee')->withErrors(['index_value_null'=>'No previous index value found - fatal error']);
        }

        $prev_index_id = $prev_index->id;
        $prev_index_value = $prev_index->reading_value;
        $start_date = $prev_index->reading_date;

        if ($index_value < $prev_index_value) {
            return redirect()->to('/enter_index_employee')->withErrors(['index_value_error'=>'Please enter an index number higher than previous value']);
        }

        // if ($index_value > $estimation->estimation_total) {
        //     return redirect()->to('/enter_index_employee')->withErrors(['index_value_error'=>'Suspiciously high value']);
        // }

        $current_index_id = DB::table('index_values')->insertGetId(
            ['reading_date' => $date, 'meter_id' => $meter_id, 'reading_value' => $index_value]
        );

        $consumption_value = $index_value - $prev_index_value;

        DB::table('consumptions')->insert(
            ['start_date' => $start_date,
            'end_date' => $date,
            'consumption_value' => $consumption_value,
            'prev_index_id' => $prev_index_id,
            'current_index_id' => $current_index_id]
        );


        DB::table('meter_reader_schedules')
            ->where('meter_id', '=', $meter_id)
            ->update(['status' => 'read']);
        return redirect()->back();
    }

    public function searchIndexPaper(Request $request)  {
        if($request->ajax())
        {
            $today = Carbon::now()->toDateString();
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
                            ->where('users.index_method', '=', 'paper')
                            ->where('meter_reader_schedules.reading_date','=', $today)
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
                        ->where('users.index_method', '=', 'paper')
                        ->where('meter_reader_schedules.reading_date','=', $today)
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

    public function GasElectricity($userID)
    {
        $query =  DB::table('users')
        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
        ->join('addresses','customer_addresses.id','=','addresses.id')
        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
        ->join('meters','meter_addresses.meter_id','=','meters.id')
        ->where('users.id', '=', $userID)
        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id')
        ->get();

        return view('Meters/Meter_History', ['details' => $query]);
    }

    public function fetchIndex($meterID) {
        $prev_index = DB::table('index_values')
            ->join('consumptions', 'consumptions.current_index_id', '=', 'index_values.id')
            ->where('index_values.meter_id', '=', $meterID)
            ->select('index_values.id', 'index_values.reading_value', 'index_values.reading_date')
            ->orderBy('consumptions.id', 'desc')
            ->get()
            ->first();

        $meter = Meter::find($meterID);

        if($prev_index) {
            return response()->json([
                'status'=>200,
                'prev_index'=> $prev_index,
                'meter'=>$meter
            ]);
        }
    }

    public function submitIndexCustomer(Request $request) {
        $errors = new MessageBag();
        $request->validate([
            'meter_id' => 'required',
            'EAN' => 'required',
            'index_value' => 'required|integer'
        ],
        [
            'meter_id.required' => 'Meter ID inclusion failed for unknown reasons.',
            'index_value.required' => 'Please enter an index value!',
            'index_value.integer' => 'You have to type in a number for the index value.'
        ]);

        $date = Carbon::now()->toDateString();
        $meter_id = $request->input('meter_id');
        $EAN = $request->input('EAN');
        $index_value = $request->input('index_value');

        $prev_index = DB::table('index_values')
        ->join('consumptions', 'consumptions.current_index_id', '=', 'index_values.id')
        ->where('index_values.meter_id', '=', $meter_id)
        ->select('index_values.id', 'index_values.reading_value', 'index_values.reading_date')
        ->orderBy('consumptions.id', 'desc')
        ->get()
        ->first();

        if ($prev_index == null) {
            return redirect()->to('/Meter_History')->withErrors(['index_value_null'=>'No previous index value found - fatal error']);
        }

        $prev_index_id = $prev_index->id;
        $prev_index_value = $prev_index->reading_value;
        $start_date = $prev_index->reading_date;

        if ($index_value < $prev_index_value) {
            return redirect()->to('/Meter_History')->withErrors(['index_value_error'=>'Please enter an index number higher than previous value']);
        }

        $current_index_id = DB::table('index_values')->insertGetId(
            ['reading_date' => $date, 'meter_id' => $meter_id, 'reading_value' => $index_value]
        );

        $consumption_value = $index_value - $prev_index_value;

        DB::table('consumptions')->insert(
            ['start_date' => $start_date,
            'end_date' => $date,
            'consumption_value' => $consumption_value,
            'prev_index_id' => $prev_index_id,
            'current_index_id' => $current_index_id]
        );

        Mail::to('anu01872@gmail.com')->send(new IndexValueEnteredByCustomer($EAN, $index_value, $date, $consumption_value));
        return redirect('Meter_History');
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

    public function showConsumptionHistory($timeframe = 'month')
    {
    $query = DB::table('index_values')
        ->where('meter_id', 6) // Auth::id())
        ->orderBy('reading_date');

    switch ($timeframe) {
        case 'week':
            $query->whereDate('reading_date', '>=', Carbon::now()->startOfWeek())
                ->whereDate('reading_date', '<=', Carbon::now()->endOfWeek());
            break;
        case 'month':
            $query->whereDate('reading_date', '>=', Carbon::now()->startOfMonth())
                ->whereDate('reading_date', '<=', Carbon::now()->endOfMonth());
            break;
        case 'year':
            $query->whereDate('reading_date', '>=', Carbon::now()->startOfYear())
                ->whereDate('reading_date', '<=', Carbon::now()->endOfYear());
            break;
        case 'all':
            break;
    }

    $consumptionData = $query->get();

    // If you want to return JSON response
    return response()->json(['consumptionData' => $consumptionData]);

    // return view('Meters/Meter_History', ['consumptionData' => $consumptionData]);
}

    public function showConsumptionPage()
{
    // Assuming you want to display consumption data as a view
    $consumptionData = $this->showConsumptionHistory('month');

    // Pass the data directly to the view
    return view('Meters/Meter_History', ['consumptionData' => $consumptionData]);
}
}

