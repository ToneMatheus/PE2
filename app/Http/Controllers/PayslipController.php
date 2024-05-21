<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payslips;
use Carbon\Carbon;

class PayslipController extends Controller
{
    public function payslips()
    {
        $userID = Auth::id();
        $id = request()->input('id');
    
        $payslipInfo = DB::select("select * from payslips where employee_profile_id = $userID");//fetching payslip plus contract information
    
        foreach($payslipInfo as $info){
            $start = htmlspecialchars($info->start_date);
            $end = htmlspecialchars($info->end_date);
            $issued = htmlspecialchars($info->creation_date);
            $hours = htmlspecialchars($info->total_hours);
            $amountPerHour = htmlspecialchars($info->amount_per_hour);
            $daysWorked = htmlspecialchars($info->nbr_days_worked);
            $IBAN = htmlspecialchars($info->IBAN);
            $totalAmount = $hours * $amountPerHour;
        }
    
        //selecting the employee's job
        $jobb = DB::select("select job, department from employee_profiles where id = $userID");
        $job = htmlspecialchars($jobb[0]->job);
        $dept = htmlspecialchars($jobb[0]->department);
    
        //selecting employee information
        $users = DB::select("select * from users where employee_profile_id = $userID");
        foreach ($users as $user) {
            $lastName = htmlspecialchars($user->last_name);
            $firstName = htmlspecialchars($user->first_name);
          
            //fetching user address from the db
            $users_id = $users[0]->id;
            $cust_add = DB::select("select address_id from customer_addresses where user_id = $users_id");
            $add_id = $cust_add[0]->address_id;
            $addressID = DB::select("select * from addresses where id = $add_id");
            $addressID = $addressID[0]->id;
    
            $address = DB::select("select * from addresses where id = $addressID");
            foreach ($address as $add){
              $street = htmlspecialchars($add->street);
              $num = htmlspecialchars($add->number);
              $pC = htmlspecialchars($add->postal_code);
              $box = htmlspecialchars($add->box);
              $city = htmlspecialchars($add->city);
              $province = htmlspecialchars($add->province);
    
              $userAddress = "" . $street . " " . $num . " " . $box . ", " . $pC . " " . $city . ". " . $province . ".";//joining the address into one long address
            }
    
        }
    
        //to select the number of holidays taken by the employee
        $holidays = DB::select("select start_date, end_date from holidays where employee_profile_id = $userID");
        $numRows = count($holidays);
    
        if(!empty($numRows)){
            $holidayStart = Carbon::parse(htmlspecialchars($holidays[0]->start_date));
            $holidayEnd = Carbon::parse(htmlspecialchars($holidays[0]->end_date));
    
            $differenceInDays = $holidayEnd->diffInDays($holidayStart);
        }
        else{
            $differenceInDays = 0;
        }
    
        $newAmount = $differenceInDays * $amountPerHour;
        $newTotalAmount = $totalAmount - $newAmount;
        $TVA = $newTotalAmount * 0.21;
        $total = $newTotalAmount - $TVA;
    
        return view('payslipView', compact('userID', 'start', 'end', 'issued', 'hours', 'amountPerHour', 'daysWorked', 'IBAN', 'totalAmount', 'job', 'dept', 'lastName', 'firstName', 'street', 'num', 'box', 'pC', 'city', 'province', 'differenceInDays', 'newTotalAmount', 'TVA', 'total'));
    }
}
