<?php
    namespace App\Http\Controllers;
    
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use PDF;
    use Illuminate\Support\Facades\Auth; 

    class DomPDFController extends Controller{
        public function getPaySlipPDF(Request $request){
            $userID = Auth::id();
        
            $payslipInfo = DB::select("select * from payslips where employee_profile_id = $userID");
            $role = DB::select("select *from roles inner join user_roles on user_roles.role_id = roles.id where user_roles.user_id = " . Auth::id());
        
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
        
            $jobb = DB::select("select job from employee_profiles where id = $userID");
            $job = htmlspecialchars($jobb[0]->job);
        
            $users = DB::select("select * from users where employee_profile_id = $userID");
            foreach ($users as $user) {
                $lastName = htmlspecialchars($user->last_name);
                $firstName = htmlspecialchars($user->first_name);
        
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
        
                    $userAddress = "" . $street . " " . $num . " " . $box . ", " . $pC . " " . $city . ". " . $province . ".";
                }
            }
        
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
        
            $data = [
                'title' => 'Your Payslip',
                'userID' => $userID,
                'start' => $start,
                'end' => $end,
                'issued' => $issued,
                'hours' => $hours,
                'amountPerHour' => $amountPerHour,
                'daysWorked' => $daysWorked,
                'IBAN' => $IBAN,
                'totalAmount' => $totalAmount,
                'job' => $job,
                'lastName' => $lastName,
                'firstName' => $firstName,
                'street' => $street,
                'num' => $num,
                'box' => $box,
                'pC' => $pC,
                'city' => $city,
                'province' => $province,
                'differenceInDays' => $differenceInDays,
                'newTotalAmount' => $newTotalAmount,
                'TVA' => $TVA,
                'total' => $total,
                'role' => $role
            ];
        
            $pdf = PDF::loadView('payslipView', $data);
        
            return $pdf->download($firstName . " " . $lastName . '\'s payslip.pdf');
        }

        public function getContractPDF(Request $request){
            $userID = Auth::id();
        
            $contract = DB::select("select * from employee_contracts where employee_profile_id = $userID");
            $employee_profile = DB::select("select * from employee_profiles where id = $userID");
            $emp_id = $employee_profile[0]->id;
            $user = DB::select("select * from users where employee_profile_id = $emp_id");
            $user_id = $user[0]->id;
            $address = DB::select("select * from customer_addresses where user_id = $user_id");
            $addressID = htmlspecialchars($address[0]->address_id);
            $emp_address = DB::select("select * from addresses where id = $addressID");
            $payslips = DB::select("select IBAN, amount_per_hour from payslips where employee_profile_id = $userID");
            $role = DB::select("select * from roles inner join user_roles on user_roles.role_id = roles.id where user_roles.user_id = " . Auth::id());
            $benefits = DB::select("select * from employee_benefits where role_id = " . $role[0]->role_id);
            $salary = DB::select("select * from salary_ranges where role_id = " . $role[0]->role_id);
        
            // Assigning data to variables
            $contract_start_date = $contract[0]->start_date;
            $company_name = "Energy company";
            $company_address = "Jan Pieter de Nayerlaan 5, 2860 Sint-Katelijne-Waver";
            $employee_name = "" . $user[0]->first_name . " " . $user[0]->last_name . "";
            $employee_address = "" . $emp_address[0]->street . " " . $emp_address[0]->number . " " . $emp_address[0]->box . ", " . $emp_address[0]->postal_code . " " . $emp_address[0]->city . ". " . $emp_address[0]->province . "";
            $job_title = $employee_profile[0]->job;
            $amount_per_hour = $payslips[0]->amount_per_hour;
            $account_number = $payslips[0]->IBAN;
        
            // Adding fetched data to the $data array
            $data = [
                'title' => 'Your Contract',
                'role' => $role,
                'salary' => $salary,
                'benefits' => $benefits,
                'contract_start_date' => $contract_start_date,
                'company_name' => $company_name,
                'company_address' => $company_address,
                'employee_name' => $employee_name,
                'employee_address' => $employee_address,
                'job_title' => $job_title,
                'amount_per_hour' => $amount_per_hour,
                'account_number' => $account_number
            ];
        
            $pdf = PDF::loadView('contractView', $data);
        
            return $pdf->download($user[0]->first_name . " " . $user[0]->last_name . '\'s contract.pdf');
        }
        

        public function getBenefitsPDF(Request $request){
            $data = [
                'title' => 'Your benefits'
            ];

            $pdf = PDF::loadView('employeeBenefitsView', $data);

            return $pdf->download('employee_benefits.pdf');
        }
    }
?>