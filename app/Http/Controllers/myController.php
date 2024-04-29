<?php
    namespace App\Http\Controllers;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth; 

    class myController extends Controller{
        public function profile(Request $request){
            if ($request->id) {
                $userID = $request->id;
            } else {
                $userID = Auth::id();
            }
            
            $users = DB::select("select * from users where id = $userID");//selecting employee information

            if (!empty($users)) {
                foreach ($users as $user) {
                    $lastName = htmlspecialchars($user->last_name);
                    $firstName = htmlspecialchars($user->first_name);
                    $email = htmlspecialchars($user->email);
                    $phone = htmlspecialchars($user->phone_nbr);
                    $employeeProfileID = htmlspecialchars($user->employee_profile_id);
                    $nationality = htmlspecialchars($user->nationality);
                    $title = htmlspecialchars($user->title);
                    
                    //fetching user address from the db
                    $employee_profile = DB::select("select * from employee_profiles where id = $userID");
                    $emp_id = $employee_profile[0]->id;
                    $user = DB::select("select * from users where employee_profile_id = $emp_id");
                    //$user_id = $user[0]->id;
                    $address = DB::select("select * from customer_addresses where user_id = $userID");
                    $addressID = htmlspecialchars($address[0]->address_id);
                    $emp_address = DB::select("select * from addresses where id = $addressID");

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

                    $empDetails = DB::select("select * from employee_profiles where id = $employeeProfileID");
                    foreach ($empDetails as $detail){
                        $notes = htmlspecialchars($detail->notes);
                        $job = htmlspecialchars($detail->job);
                        $notes = explode(',', $detail->notes);
                    }
                }

                $contract = DB::select("select * from employee_contracts where employee_profile_id = $employeeProfileID");//fetching payslip plus contract information
                foreach($contract as $info){
                    $start = htmlspecialchars($info->start_date);
                    $end = htmlspecialchars($info->end_date);
                }

                //fetch holiday balance for this employee
                $balance = DB::select("select * from balances where employee_profile_id = $employeeProfileID");

                $team = DB::select("select * from team_members inner join teams on team_members.team_id = teams.id where user_id = $userID");
                $team_name = htmlspecialchars($team[0]->team_name);

                //the team leader
                $team_leader = DB::select("SELECT * FROM team_members INNER JOIN teams ON team_members.team_id = teams.id WHERE teams.team_name = ? AND team_members.is_manager = 1", [$team_name]);
                $team_leader_details = DB::select("select * from users where id = " . $team_leader[0]->user_id);
            } 

            return view('profile', compact('firstName', 'lastName', 'email', 'phone', 'employeeProfileID', 'nationality', 'team_name', 'team_leader_details', 'userID', 'userAddress', 'job'));
            
        }                

        public function payslip(Request $request){
            // Retrieve the value of the 'flag' parameter
            $id = $request->input('id');
    
            // You can use $flag as needed
            $userID = Auth::id();
        
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
        
            //return view('payslip', ['id' => $id]);
            return view('payslip', compact('userID', 'start', 'end', 'issued', 'hours', 'amountPerHour', 'daysWorked', 'IBAN', 'totalAmount', 'job', 'dept', 'lastName', 'firstName', 'street', 'num', 'box', 'pC', 'city', 'province', 'differenceInDays', 'newTotalAmount', 'TVA', 'total'));
        }

        public function payList(){
            return view('payList');
        }

        public function contract(){
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

            //making variables to hold the info that way i don't have to do much when its time to actually fetch the data from the database
            //$contract_end_date = $contract[0]->;
            $contract_start_date = $contract[0]->start_date;
            $company_name = "Energy company";
            $company_address = "Jan Pieter de Nayerlaan 5, 2860 Sint-Katelijne-Waver";
            $employee_name = "" . $user[0]->first_name . " " . $user[0]->last_name . "";
            $employee_address = "" . $emp_address[0]->street . " " . $emp_address[0]->number . " " . $emp_address[0]->box . ", " . $emp_address[0]->postal_code . " " . $emp_address[0]->city . ". " . $emp_address[0]->province . ".";
            $job_title = $employee_profile[0]->job;

            $amount_per_hour = $payslips[0]->amount_per_hour;
            $account_number = $payslips[0]->IBAN;

            return view('contract', compact('contract_start_date', 'company_name', 'company_address', 'employee_name', 'employee_address', 'job_title', 'amount_per_hour', 'account_number'));
        }

        public function tariff(){
            return view('tariff');
        }

        public function contractOverview(){
            return view('customer.contractOverview');
        }

        public function manager(Request $request){
            $manager_id = Auth::id();
            $decision_date = 0;

            if ($request->input('decline') == 1) {
                $id = $request->input('id');
                $request_id = $request->input('req_id');
                DB::update("update holidays set  manager_approval = 0, boss_approval = 0, is_active = 0 where id = $request_id and employee_profile_id = $id");
                $decision_date = Carbon::today();
                $decision_date = $decision_date->format('Y-m-d');
            }
            if ($request->input('accept') == 1) {
                $id = $request->input('id');
                $request_id = $request->input('req_id');
                DB::update("update holidays set manager_approval = 1, boss_approval = 1, is_active = 0 where id = $request_id and employee_profile_id = $id");
                $decision_date = Carbon::today();
                $decision_date = $decision_date->format('Y-m-d');
            }
    
            $manager_user = DB::select("select * from users where id = $manager_id");
    
            $team_members = [];
            $manager_team = DB::select("select team_id from team_members where user_id = $manager_id");
            $employee_manager_relation = DB::select("select * from team_members where team_id = " . $manager_team[0]->team_id . " and is_manager = 0");
    
            $all_requests = [];
            $all_data = [];//to select everything in the table
    
            foreach ($employee_manager_relation as $relation) {
                $team_members = DB::select("select employee_profile_id from users where id = $relation->user_id");
                $emp_profile_id = $team_members[0]->employee_profile_id;
                
                if(!empty($emp_profile_id)){
                    $requests = DB::select("SELECT * FROM holidays WHERE employee_profile_id = $emp_profile_id AND is_active = 1");
                    $all_requests = array_merge($all_requests, $requests);

                    $table_data = DB::select("select * from holidays where employee_profile_id = $emp_profile_id");
                    $all_data = array_merge($all_data, $table_data);
                }
                
            }

            $number_employees = 0;

            $all_requests2 = [];

            foreach ($employee_manager_relation as $relation) {
                $team_members = DB::select("select * from users where id = $relation->user_id");

                if(!empty($team_members[0]->employee_profile_id)){
                    // Fetch holidays for the current employee
                    $requests = DB::select("select * from holidays where employee_profile_id = " . $team_members[0]->employee_profile_id . " and is_active = 0 and manager_approval = 1 order by start_date");
                    
                    // Append the requests for the current employee to the array
                    $all_requests2 = array_merge($all_requests2, $requests);

                    $number_employees++;
                }

            }
            
            return view('managerPage', compact('decision_date', 'all_data', 'number_employees', 'all_requests2', 'manager_id', 'manager_user', 'employee_manager_relation', 'all_requests'));
        }

        public function employeeList(){
            return view('employeeList');
        }

        public function holiday(){
            return view('holidayRequestPage');
        }

        public function managerList(){
            return view('managerList');
        }

        public function benefits(){
            return view('employeeBenefits');
        }

        public function store(Request $request, $id)
        {
           // if ($request->input('input_data') != null) {
                // Fetch the existing data from the database
                $existingData = DB::table('employee_profiles')->where('id', $id)->value('notes');
                
                if ($request->input('action') == 'add') {
                    // Get the new data from the request
                    $newData = $request->input('input_data');
        
                    // Concatenate the new data with the existing data
                    $concatenatedData = htmlspecialchars($newData . ',' . $existingData);
        
                    // Update the database record with the concatenated value
                    DB::table('employee_profiles')->where('id', $id)->update(['notes' => $concatenatedData]);
                } 
                
                if ($request->input('action') == 'del') {
                    $notes = explode(',', $existingData); // Corrected: explode the string directly
                    $noteToDelete = $request->input('note');
                    if (($key = array_search($noteToDelete, $notes)) !== false) {
                        unset($notes[$key]); // Remove the note from the array
                        $updatedString = implode(',', $notes); // Join the array back into a string
                
                        // Update the database record
                        DB::table('employee_profiles')->where('id', $id)->update(['notes' => $updatedString]);
                    }
                }
           // }
            return view('profile');
        }

        public function documents(){
            return view('documents');
        }

        public function jobs(){
            return view('HR_EmployeeJobs.jobOffers');
        }

        public function hiringManager(){
            return view('HR_EmployeeJobs.hiringManager');
        }

        public function jobDescription(){
            return view('HR_EmployeeJobs.jobDescription');
        }

        public function jobApply(){
            return view('HR_EmployeeJobs.jobApply');
        }

        public function finance(){
            return view('financialAnalystPage');
        }
    }
?>