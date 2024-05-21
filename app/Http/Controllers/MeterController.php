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
    Invoice,
    Contract_product,
    Invoice_line,
    CreditNote,
    Estimation,
    Discount,
    Index_Value
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Bus\Dispatcher;
use app\http\Controllers\CustomerController;
use illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\IndexValueEnteredByCustomer;
use App\Mail\InvoiceLandlordMail;
use Illuminate\Support\Facades\Log;
use App\Jobs\FinalSettlementJob;
use App\Services\InvoiceFineService;
use App\Services\StructuredCommunicationService;
use App\Mail\FinalSettlementMail;
use App\Http\Controllers\EstimationController;
use Illuminate\Support\Facades\Crypt;


class MeterController extends Controller
{
    protected $auth_user;
    protected $auth_userID;
    protected $domain;

    public function __construct()
    {
        $this->auth_user = Auth::user();
        $this->auth_userID = Auth::id();
        $this->domain = 'http://127.0.0.1:8000';
        // $this->domain = config('app.host_domain');
    }

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
                    ->join('addresses','customer_addresses.address_id','=','addresses.id')
                    ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                    ->join('meters','meter_addresses.meter_id','=','meters.id')
                    ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                    ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
                    ->where('meter_reader_schedules.employee_profile_id','=', $request->user()->id)
                    ->where('meter_reader_schedules.status','=','unread')
                    ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.id', 'meters.type', 'meter_reader_schedules.priority')
                    ->get();

        $employeeName = DB::table('users')
                        ->where('users.id', '=', $request->user()->id)
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
                        ->join('team_members', 'team_members.user_id', '=', 'u.id')
                        ->where('team_members.team_id', '=', 3)
                        ->where('team_members.is_manager', '=', 0)
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
                            ->join('addresses','customer_addresses.address_id','=','addresses.id')
                            ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                            ->join('meters','meter_addresses.meter_id','=','meters.id')
                            ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                            ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                            ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
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
                        ->join('addresses','customer_addresses.address_id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                        ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                        ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
                        ->where('meter_reader_schedules.status','=','unread')
                        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code', 'addresses.city', 'meters.EAN', 'meters.ID as meter_id', 'meter_reader_schedules.id', 'e.first_name as assigned_to')
                        ->orderBy('users.id');
            }

            $employees = DB::table('users as u')
                        ->join('team_members', 'team_members.user_id', '=', 'u.id')
                        ->where('team_members.team_id', '=', 3)
                        ->where('team_members.is_manager', '=', 0)
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
                    <td class="text-center px-2 border dark:border-gray-700">'.$i.'</td>
                    <td class="font-bold px-2 border dark:border-gray-700">'.$row->first_name.' '.$row->last_name.'</td>
                    <td class="border px-2 dark:border-gray-700">'.$row->street.' '.$row->number.', '.$row->city.'</td>
                    <td class="text-center px-2 border dark:border-gray-700">'.$row->assigned_to.'</td>';

                    $output .= '
                    <td class="border px-2 text-center dark:border-gray-700">
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
                    <button type="submit" onmouseover="this.style.color=\'red\';" onmouseout="this.style.color=\'white\';" style="background-color:#000000;color:white;padding:5px 10px;border-radius:10px;margin-left:10px;">Apply changes</button>
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
            $output = '';
            $queryName = $request->get('queryName');
            $queryEAN = $request->get('queryEAN');
            $queryCity = $request->get('queryCity');
            $queryStreet = $request->get('queryStreet');
            $queryNumber = $request->get('queryNumber');

            if($queryName != '' || $queryEAN != '' || $queryCity != '' || $queryStreet != '' || $queryNumber != '') { // getting all the required data for the table
                $query = DB::table('users')
                            ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                            ->join('addresses','customer_addresses.address_id','=','addresses.id')
                            ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                            ->join('meters','meter_addresses.meter_id','=','meters.id')
                            ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                            ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                            ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
                            ->where('meter_reader_schedules.employee_profile_id','=', $request->user()->id)
                            ->where('users.index_method', '=', 'website')
                            ->where('users.is_active','=', 1)
                            ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code',
                                    'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id', 'meter_reader_schedules.id',
                                    'meter_reader_schedules.priority', 'meter_reader_schedules.status', 'e.first_name as assigned_to')
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
                        ->join('addresses','customer_addresses.address_id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                        ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                        ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
                        ->where('meter_reader_schedules.employee_profile_id','=', $request->user()->id)
                        ->where('users.index_method', '=', 'website')
                        ->where('users.is_active','=', 1)
                        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code',
                                'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id', 'meter_reader_schedules.id',
                                'meter_reader_schedules.priority', 'meter_reader_schedules.status', 'e.first_name as assigned_to')
                        ->orderBy('users.id');
            }

            $data = $query->get();
            $total_row = $data->count();
            if($total_row > 0){
                foreach($data as $row)
                {
                    $output .= '<div class="p-4 my-3 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg text-gray-500 dark:text-gray-400 grid grid-cols-2 gap-4 searchResult';
                    
                    if($row->priority == 1 && $row->status == "unread") {
                        $output .= ' priority ';
                    }

                    if ($row->status == "read") {
                        $output .= ' readMeter">';
                    }

                    if ($row->status == "unread") {
                        $output .= '">';
                    }
                    $output .= '<div class="searchResultLeft text-2xl">
                                <p>Name: <span class="text-gray-800 dark:text-white font-semibold">'.$row->first_name.' '.$row->last_name.'</span></p>
                                <p>EAN code: <span class="text-gray-800 dark:text-white font-semibold">'.$row->EAN.'</span></p>
                                <p>Type: <span class="text-gray-800 dark:text-white font-semibold">'.$row->type.'</span></p>
                                <p>Address: '.$row->street.' '.$row->number.', '.$row->city.'</span></p>
                            </div>
                            <div class="searchResultRight text-right text-2xl ">
                                <span>Status:</span><br>
                                    <span class="my-2" style="font-size:50px;color:';
                                    if ($row->status == "unread") {
                                        if($row->priority == 1) {
                                            $output .= 'red;font-weight:bold;">'.ucfirst($row->status) .'(Priority)</span>';
                                        }
                                        else {
                                            $output .= 'red;font-weight:bold;">'.ucfirst($row->status).'</span>';
                                        }
                                        
                                        $output .= '
                                            <p><button type="button" class="modalOpener bg-gray-800 dark:bg-gray-800 text-white text-xl p-2 shadow rounded-lg" value='.$row->meter_id.'>Add index value</button></p>
                                        ';
                                    }
                                    else {
                                        $output .= 'green;font-weight:bold;">'.ucfirst($row->status).'</span>';
                                    }

                        $output .= '
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
        $testDate = Carbon::now()->addDays(14)->toDateString();
        $testDateIn = Carbon::now()->addDays(15)->toDateString();
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

        $contract_date = DB::table('users')
        ->join('customer_contracts','users.id','=','customer_contracts.user_id')
        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
        ->join('addresses','customer_addresses.address_id','=','addresses.id')
        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
        ->join('meters','meter_addresses.meter_id','=','meters.id')
        ->where('users.is_active', '=', 1)
        ->where('meters.id', '=', $meter_id)
        ->select('customer_contracts.end_date', 'customer_contracts.start_date', 'addresses.*', 'users.first_name', 'users.last_name', 'users.id as user_id')
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
            ['reading_date' => config('app.metersDate'), 'meter_id' => $meter_id, 'reading_value' => $index_value]
        );

        $consumption_value = $index_value - $prev_index_value;

        $consumptionID = DB::table('consumptions')->insertGetId(
            ['start_date' => $start_date,
            'end_date' => config('app.metersDate'),
            'consumption_value' => $consumption_value,
            'prev_index_id' => $prev_index_id,
            'current_index_id' => $current_index_id]
        );

        DB::table('meters')
            ->where('id', '=', $meter_id)
            ->update(['expecting_reading' => '0']);

        DB::table('meter_reader_schedules')
            ->where('meter_id', '=', $meter_id)
            ->where('status', '=', 'unread')
            ->limit(1)
            ->update(['status' => 'read']);

        if ($contract_date->end_date == config('app.metersDate')) {
            DB::table('customer_contracts')
            ->where('user_id', '=', $contract_date->user_id)
            ->update(['status' => 'Inactive']);

            DB::table('users')
            ->where('id', '=', $contract_date->user_id)
            ->update(['is_active' => '0']);

            DB::table('users')
                        ->join('customer_addresses','users.id','=','customer_addresses.user_id')
                        ->join('customer_contracts','users.id','=','customer_contracts.user_id')
                        ->join('addresses','customer_addresses.address_id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->where('customer_contracts.start_date', '>', $contract_date->end_date)
                        ->where('meters.id', '=', $meter_id)
                        ->update(['users.is_active' => '1', 'customer_contracts.status' => 'Active']);

            $this->finalSettlementJob($meter_id, $consumptionID, $consumption_value);
        }
        if ($contract_date->start_date == config('app.metersDate')) {
            Log::info("Contract:", ["contract"=>$contract_date]);
            Mail::to(config('app.email'))->send(new InvoiceLandlordMail($this->domain, $contract_date, $index_value, config('app.metersDate'), $consumption_value));
        }

        
        return redirect()->back();
    }

    public function landlordSettlement() {

    }

    public function finalSettlementJobAlt($meter_id) {
        Log::debug("Final settlement sent to meter " . $meter_id);
    }

    public function finalSettlementJob($meter_id, $consumptionID, $consumption_value) {
        $domain = "http://127.0.0.1:8000";
        $now = Carbon::now()->toDateString();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $due = Carbon::parse(config('app.metersDate'))->addWeeks(2)->toDateString();

        $consumptionData = DB::table('consumptions')->where('id', '=', $consumptionID)->get()->first();
        try
        {
            $meter = Meter::findOrFail($meter_id);
            Log::info(['meterid'=>$meter_id]);
            Log::info(['meter'=>$meter]);
        }
        catch (\Exception $e)
        {
            Log::error("Unable to find meter record of with meterID " . $meter_id);
        }
        
        try
        {
            $result = Meter::select('cc.id', 'cc.user_id')
                ->leftJoin('contract_products as cp', 'meters.id', '=', 'cp.meter_id')
                ->leftJoin('customer_contracts as cc', 'cp.customer_contract_id', '=', 'cc.id')
                ->where('meters.id', $meter_id)
                ->first();
            Log::info(['result'=>$result]);
            $ccID = $result->id;
            $userID = $result->user_id;
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            // no result found
            Log::error("Unable to retrieve customerContractID and userID");
        }

        try
        {

            //get estimation for this meter
            $estimation = Estimation::where('meter_id', $meter_id)->value('estimation_total');

            if (is_null($estimation))
            {
                throw new \Exception("Couldn't find estimation.");
            }

            //discounts
            $discounts = Discount::rightJoin('contract_products as cp', 'cp.id', '=', 'discounts.contract_product_id')
                ->where('cp.meter_id', '=', $meter_id)
                ->whereDate('discounts.end_date', '>=', $now)
                ->get();

            //get tariff rate
            $tariffRate = Contract_product::leftJoin('products as p', 'contract_products.product_id', '=', 'p.id')
                ->leftJoin('product_tariffs as pt', 'p.id', '=', 'pt.product_id')
                ->leftJoin('tariffs as t', 'pt.tariff_id', '=', 't.id')
                ->whereNull('pt.end_date')
                ->where('contract_products.customer_contract_id', $ccID)
                ->where('contract_products.meter_id', $meter_id)
                ->orderByDesc('contract_products.start_date')
                ->value('t.rate');

            if (is_null($tariffRate))
            {
                throw new \Exception("Couldn't find tariff rate.");
            }

            //get meter readings
            $meterReadings = Index_Value::where('meter_id', $meter_id)
            ->orderByDesc('reading_date')
            ->limit(1)
            ->first();

            if (is_null($meterReadings))
            {
                throw new \Exception("Couldn't find meter readings.");
            }
        }
        catch (\Exception $e) 
        {
            Log::error("Unable to find information for meter " . $meter_id . " in database. " . $e->getMessage());
            exit();
        }

        //calculate discounts
        $extraAmount = 0;

        if(!is_null($discounts)){
            for ($i = 1; $i <= 12; $i++) {
                $discountRate = 0;
            
                foreach ($discounts as $discount) {
                    $startMonth = (new Carbon($discount->start_date))->format('m');
                    $endMonth = (new Carbon($discount->end_date))->format('m');
            
                    if ($i >= $startMonth && $i <= $endMonth) {
                        $discountRate = $discount->rate;
                        break;
                    }
                }

                $monthlyExtraAmount = ($consumption_value) * $tariffRate;

                if ($discountRate > 0) {
                    $monthlyExtraAmount -= ($monthlyExtraAmount * $discountRate);
                }
            
                $extraAmount += $monthlyExtraAmount;
            }
        } else {
            $extraAmount = ($consumption_value) ? $consumption_value * $tariffRate : 0;
        }

        //calculate
        $monthlyInvoices = $this->getMonthlyInvoices($year, $ccID, $meter_id);

        if($extraAmount > 0){                   //Invoice
            $invoiceData = [
                'invoice_date' => config('app.metersDate'),
                'due_date' => $due,
                'total_amount' => $extraAmount,
                'status' => 'sent',
                'customer_contract_id' => $ccID,
                'meter_id' => $meter_id,
                'type' => 'Final'
            ];

        } else{                              //Credit note
            $invoiceData = [
                'invoice_date' => config('app.metersDate'),
                'due_date' => $due,
                'total_amount' => $extraAmount,
                'status' => 'paid',
                'customer_contract_id' => $ccID,
                'meter_id' => $meter->id,
                'type' => 'Final'
            ];
        }

        // 3) store in database
        try
        {
            $invoice = Invoice::create($invoiceData);
            $lastInserted = $invoice->id;

            $scService = new StructuredCommunicationService;
            $strCom = $scService->generate($lastInserted);
            $scService->addStructuredCommunication($strCom, $lastInserted);

            if ($extraAmount <= 0) {
                CreditNote::create([
                    'invoice_id' => $lastInserted,
                    'type' => 'credit note',
                    'amount' => $extraAmount,
                    'user_id' => $userID,
                    'status' => 1
                ]);
            }

            Invoice_line::create([
                'type' => 'Electricity',
                'unit_price' => $tariffRate,
                'amount' => $extraAmount,
                'consumption_id' => $consumptionID,
                'invoice_id' => $lastInserted
            ]);

            $fineService = new InvoiceFineService;
            $fineService->unpaidInvoiceFine($lastInserted);
        }
        catch (\Exception $e)
        {
            Log::error("Unable to store final settlement invoice for meter " . $meter_id . " in database.");
        }
        
        $newInvoiceLine = Invoice_line::where('invoice_id', '=', $lastInserted)->first();
        EstimationController::UpdateEstimation($meter_id);

        $lastAnnual = Invoice::where('invoices.type', '=', 'Annual')
            ->orderByDesc('invoices.invoice_date')
            ->limit(1)
            ->first();

        $interval = array((new Carbon($lastAnnual->invoice_date))->format('m'), $month);
        // 4) send mail
        $user = User::findOrFail($userID);

        // Generate PDF
        $hash = md5($invoice->id . $invoice->customer_contract_id . $invoice->meter_id);

        $pdfData = [
            'invoice' => $invoice,
            'user' => $user,
            'consumption' => $consumptionData,
            'estimation' => $estimation,
            'newInvoiceLine' => $newInvoiceLine,
            'meterReadings' => $meterReadings,
            'discounts' => $discounts,
            'monthlyInvoices' => $monthlyInvoices,
            'domain' => $domain,
            'hash' => $hash,
            'interval' => $interval
        ];

        $mailParams = [
            $invoice, 
            $user, 
            $pdfData, 
            $consumptionData,
            $estimation, 
            $newInvoiceLine, 
            $meterReadings, 
            $discounts, 
            $monthlyInvoices,
            $interval
        ];
        Log::info("QR code generated with link: " . $domain . "/pay/" . $invoice->id . "/" . $hash);

        if (is_null($user->employee_profile_id))
            $mailAddress = $user->email;
        else
            $mailAddress = $user->personal_email;

        //Send email with PDF attachment
        $this->sendFinalEmail(config('app.email'), FinalSettlementMail::class, $mailParams, 'Invoices.final_invoice_pdf', $pdfData, $invoice->id);
    }

    public function sendFinalEmail($mailTo, $mailableClass, $mailableClassParams, $pdfView, $pdfParams, $invoiceID) {
        $pdf = Pdf::loadView($pdfView, [
            ...$pdfParams
        ], [], 'utf-8');
        $pdfData = $pdf->output();

        Mail::to($mailTo)->send(new $mailableClass($pdfData, ...$mailableClassParams));
    }

    public function getMonthlyInvoices($year, $ccID, $meter_id) //(function copied from InvoiceRunJob)
    {
        // Query monthly invoices and their lines for the given customer and the current year
        $monthlyInvoices = Invoice::join('customer_contracts as cc', 'invoices.customer_contract_id', '=', 'cc.id')
            ->join('users as u', 'cc.user_id', '=', 'u.id')
            ->join('invoice_lines as il', 'invoices.id', '=', 'il.invoice_id')
            ->where('cc.id', $ccID)
            ->where('invoices.meter_id', '=', $meter_id)
            ->where('invoices.type', 'Monthly')
            ->whereYear('invoices.invoice_date', $year)
            ->orderBy('invoices.invoice_date')
            ->select('invoices.*', 'il.*')
            ->get();

        // Organize the data by grouping lines by invoice (month)
        $monthlyInvoicesData = [];
        foreach ($monthlyInvoices as $monthlyInvoice) {
            $month = Carbon::createFromFormat('Y-m-d', $monthlyInvoice->invoice_date)->format('F');
            $monthlyInvoicesData[$month][] = $monthlyInvoice;
        }

        return $monthlyInvoicesData;
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
                            ->join('addresses','customer_addresses.address_id','=','addresses.id')
                            ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                            ->join('meters','meter_addresses.meter_id','=','meters.id')
                            ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                            ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                            ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
                            ->where('meter_reader_schedules.employee_profile_id','=', $request->user()->id)
                            ->where('users.index_method', '=', 'paper')
                            ->where('users.is_active','=', 1)
                            ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code',
                                    'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id', 'meter_reader_schedules.id',
                                    'meter_reader_schedules.priority', 'meter_reader_schedules.status', 'e.first_name as assigned_to')
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
                        ->join('addresses','customer_addresses.address_id','=','addresses.id')
                        ->join('meter_addresses','addresses.id','=','meter_addresses.address_id')
                        ->join('meters','meter_addresses.meter_id','=','meters.id')
                        ->join('meter_reader_schedules','meters.id','=','meter_reader_schedules.meter_id')
                        ->join('users as e', 'e.employee_profile_id','=','meter_reader_schedules.employee_profile_id')
                        ->where('meter_reader_schedules.reading_date','=', config('app.metersDate'))
                        ->where('meter_reader_schedules.employee_profile_id','=', $request->user()->id)
                        ->where('users.index_method', '=', 'paper')
                        ->where('users.is_active','=', 1)
                        ->select('users.first_name', 'users.last_name', 'addresses.street', 'addresses.number', 'addresses.postal_code',
                                'addresses.city', 'meters.EAN', 'meters.type', 'meters.ID as meter_id', 'meter_reader_schedules.id',
                                'meter_reader_schedules.priority', 'meter_reader_schedules.status', 'e.first_name as assigned_to')
                        ->orderBy('users.id');
            }

            $data = $query->get();
            $total_row = $data->count();
            if($total_row > 0){
                foreach($data as $row)
                {
                    $output .= '<div class="p-4 my-3 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg text-gray-500 dark:text-gray-400 grid grid-cols-2 gap-4 searchResult';
                    
                    if($row->priority == 1 && $row->status == "unread") {
                        $output .= ' priority ';
                    }

                    if ($row->status == "read") {
                        $output .= ' readMeter">';
                    }

                    if ($row->status == "unread") {
                        $output .= '">';
                    }
                    $output .= '<div class="searchResultLeft text-2xl">
                                <p>Name: <span class="text-gray-800 dark:text-white font-semibold">'.$row->first_name.' '.$row->last_name.'</span></p>
                                <p>EAN code: <span class="text-gray-800 dark:text-white font-semibold">'.$row->EAN.'</span></p>
                                <p>Type: <span class="text-gray-800 dark:text-white font-semibold">'.$row->type.'</span></p>
                                <p>Address: '.$row->street.' '.$row->number.', '.$row->city.'</span></p>
                            </div>
                            <div class="searchResultRight text-right text-2xl ">
                                <span>Status:</span><br>
                                    <span class="my-2" style="font-size:50px;color:';
                                    if ($row->status == "unread") {
                                        if($row->priority == 1) {
                                            $output .= 'white;font-weight:bold;">'.ucfirst($row->status).'</span>';
                                        }
                                        else {
                                            $output .= 'red;font-weight:bold;">'.ucfirst($row->status).'</span>';
                                        }
                                        
                                        $output .= '
                                            <p><button type="button" class="modalOpener bg-gray-800 dark:bg-gray-800 text-white text-xl p-2 shadow rounded-lg" value='.$row->meter_id.'>Add index value</button></p>
                                        ';
                                    }
                                    else {
                                        $output .= 'green;font-weight:bold;">'.ucfirst($row->status).'</span>';
                                    }

                        $output .= '
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

    public function GasElectricity(Request $request)
    {
        if ($request->token)
        {
            try {
                $tempUserID = Crypt::decrypt($request->token);
                Log::info('Decrypted temp user ID: ', ['userID' => $tempUserID]);
                $a = 5897;
                $b = 95471;
                $c = 42353;
                $originalUserIDstr = ($b * ($tempUserID - $c)) / $a;
                $originalUserID = (int) round((float) $originalUserIDstr);
            }
            catch (\Exception $e) {
                Log::error('Decryption failed: ', ['error' => $e->getMessage()]);
            }
    
            try {
                $user = User::findOrFail($originalUserID);
                Auth::login($user);
                Log::info('Logged in');
            }
            catch (\Exception $e) {
                Log::error('Login failed');
            }
        }

        $query = 'SELECT t1.user_id, t1.first_name, t1.last_name, t1.street, t1.number, t1.postal_code, t1.city, t1.EAN, t1.expecting_reading, t1.type, t1.meter_id, t2.reading_date, t2.latest_reading_value FROM 
        (SELECT users.id AS user_id, users.first_name, users.last_name, addresses.street, addresses.number, addresses.postal_code, addresses.city, meters.EAN, meters.expecting_reading, meters.type, meters.id AS meter_id FROM `users`
        JOIN customer_addresses on users.id = customer_addresses.user_id
        JOIN addresses on customer_addresses.address_id = addresses.id
        JOIN meter_addresses on addresses.id = meter_addresses.address_id
        JOIN meters on meter_addresses.meter_id = meters.id
        WHERE users.id = '.Auth::id().' ) AS t1
        LEFT JOIN
        (SELECT index_values.meter_id, index_values.reading_date, index_values.reading_value AS latest_reading_value
        FROM index_values
        WHERE (index_values.meter_id, index_values.reading_value) IN (
            SELECT index_values.meter_id, MAX(index_values.reading_value) FROM index_values
            GROUP BY index_values.meter_id
        )) AS t2 ON t1.meter_id = t2.meter_id;';

        $result =  DB::select($query);

        $index_values = [];

        foreach($result as $row) {
            $index_value = DB::table('index_values')
                            ->join('meters', 'meters.id', '=', 'index_values.meter_id')
                            ->where('index_values.meter_id', $row->meter_id)
                            ->select('index_values.*', 'meters.EAN')
                            ->get()
                            ->toArray();

            $index_values[] = $index_value;
        }
        return view('Meters/Meter_History', ['details' => $result, 'index_values' => $index_values]);
    }

    public function ValidateIndex(Request $request) {
        if($request->ajax())
        {
            $meterID = $request->get('meterID');
            $indexValue = $request->get('indexValue');

            if ($indexValue != '') {
                $prev_index = DB::table('index_values')
                ->join('consumptions', 'consumptions.current_index_id', '=', 'index_values.id')
                ->join('estimations', 'estimations.meter_id', '=', 'index_values.meter_id')
                ->where('index_values.meter_id', '=', $meterID)
                ->select('index_values.id', 'index_values.reading_value', 'index_values.reading_date', 'estimations.estimation_total')
                ->orderBy('consumptions.id', 'desc')
                ->get()
                ->first();

                if ($prev_index == null) {
                    $prev_index_value = 0;
                    $estimation = $prev_index->estimation_total;
                }
                else {
                    $prev_index_value = $prev_index->reading_value;
                    $estimation = $prev_index->estimation_total;
                }

                if (!is_numeric($indexValue)) {
                    echo '<div class="p-2 w-full bg-rose-200 dark:bg-rose-300 rounded-lg flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#be123c" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="ml-4 text-red-700">Please enter a valid number.</p>
                                </div>';
                }
                elseif ($indexValue < $prev_index_value) {
                    echo '<div class="p-2 w-full bg-rose-200 dark:bg-rose-300 rounded-lg flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#be123c" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="ml-4 text-red-700">New index value can\'t be lower than previous index value.</p>
                                </div>';
                }
                elseif ($indexValue > ($estimation + 0.5 * $estimation)) {
                    echo '<div class="p-2 w-full bg-rose-200 dark:bg-rose-300 rounded-lg flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#be123c" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="ml-4 text-red-700">New index value can\'t be higher than the estimation. Please check if you entered the right value.</p>
                                </div>';
                }
                else {
                    echo '<div class="p-2 w-full bg-green-200 dark:bg-green-300 rounded-lg flex correct">
                            
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#15803d" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="ml-4 text-green-700">All correct!</p>
                                </div>';
                }
            }
            else {
                echo '<div class="p-2 w-full bg-rose-200 dark:bg-rose-300 rounded-lg flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#be123c" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="ml-4 text-red-700">Please enter a value.</p>
                                </div>';
            }
        }
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
        $date = Carbon::now()->toDateString();

        $index_values = $request->input('index_values');

        foreach($index_values as $index_value) {
            $meter_id = $index_value['meter_id'];
            $new_index_value = $index_value['new_index_value'];
            $EAN = $index_value['EAN'];
            $user_id = $index_value['user_id'];

            $prev_index = DB::table('index_values')
                            ->join('consumptions', 'consumptions.current_index_id', '=', 'index_values.id')
                            ->where('index_values.meter_id', '=', $meter_id)
                            ->select('index_values.id', 'index_values.reading_value', 'index_values.reading_date')
                            ->orderBy('consumptions.id', 'desc')
                            ->get()
                            ->first();

            if ($prev_index == null) {
                $prev_index_value = 0;

                $current_index_id = DB::table('index_values')->insertGetId(
                    ['reading_date' => config('app.metersDate'), 'meter_id' => $meter_id, 'reading_value' => $new_index_value]
                );

                $consumption_value = $new_index_value - $prev_index_value;

                $installation_date = DB::table('meters')
                                    ->where('id', '=', $meter_id)
                                    ->select('installation_date')
                                    ->get()
                                    ->first();

                DB::table('consumptions')->insert(
                    ['start_date' => $installation_date->installation_date,
                    'end_date' => config('app.metersDate'),
                    'consumption_value' => $consumption_value,
                    'prev_index_id' => null,
                    'current_index_id' => $current_index_id]
                );

                $meter = Meter::find($meter_id);
                $meter->expecting_reading = 0;
                $meter->save();
            }
            else {
                $prev_index_id = $prev_index->id;
                $prev_index_value = $prev_index->reading_value;
                $start_date = $prev_index->reading_date;

                $current_index_id = DB::table('index_values')->insertGetId(
                    ['reading_date' => config('app.metersDate'), 'meter_id' => $meter_id, 'reading_value' => $new_index_value]
                );

                $consumption_value = $new_index_value - $prev_index_value;

                DB::table('consumptions')->insert(
                    ['start_date' => $start_date,
                    'end_date' => config('app.metersDate'),
                    'consumption_value' => $consumption_value,
                    'prev_index_id' => $prev_index_id,
                    'current_index_id' => $current_index_id]
                );

                $meter = Meter::find($meter_id);
                $meter->expecting_reading = 0;
                $meter->save();
            }

            Mail::to(config('app.email'))->send(new IndexValueEnteredByCustomer(config('app.host_domain'), $user_id, $EAN, $new_index_value, $date, $consumption_value));
        }
        return redirect()->back();
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
    //return response()->json(['consumptionData' => $consumptionData]);

    return view('Meters/Meter_History', ['consumptionData' => $consumptionData]);
}

    public function showConsumptionPage()
{
    // Assuming you want to display consumption data as a view
    $consumptionData = $this->showConsumptionHistory('month');

    // Pass the data directly to the view
    return view('Meters/Meter_History', ['consumptionData' => $consumptionData]);
}
}

