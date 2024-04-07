@php
use Carbon\Carbon;

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

//contract body
    echo("<u><h1 style=\"text-align: center\">Employee Contract</h1></u>
        <p>This agreement is made effective as of $contract_start_date, between:</p>

        <h5>Employer:</h5>
        <p>$company_name<br>
        Located at: $company_address</p>

        <h5>Employee:</h5>
        <p>Name: $employee_name<br>
        Address: $employee_address</p>
        <hr/>

        <h4>1. Position and Duties:</h4>
        <p>The Employer agrees to employ the Employee as $job_title. The Employee agrees to perform the duties and responsibilities associated with this position to the best of their abilities. The duties may be amended by the Employer from time to time.</p>

        <h4>2. Compensation:</h4>
        <p>The Employee will be compensated at a rate of $$amount_per_hour per hour, subject to deductions for taxes and other withholdings as required by law.</p>

        <h4>3. Payment and Benefits:</h4>
        <p>The Employee's compensation will be paid monthly through direct deposit to the bank account specified by the Employee. The account number for direct deposit is $account_number. The Employee may be eligible for additional benefits as per the company's policies.</p>

        <h4>4. Working Hours:</h4>
        <p>The standard work hours for the Employee shall be 30 hours per week. Overtime may be required from time to time, and will be compensated in accordance with applicable labor laws.</p>

        <h4>5. Probation Period:</h4>
        <p>The Employee's employment is subject to a probationary period of one month, during which either party may terminate the employment relationship with written notice.</p>

        <h4>6. Confidentiality:</h4>
        <p>The Employee agrees not to disclose any confidential information belonging to the Employer, including but not limited to trade secrets, business plans, and customer information, during or after employment.</p>

        <h4>7. Termination:</h4>
        <p>Either party may terminate this agreement with written notice. Upon termination, the Employee agrees to return all company property and materials in their possession.</p>

        <h4>8. Non-Compete:</h4>
        <p>During the term of employment and for a period of 5 months after termination, the Employee agrees not to engage in any activity that competes with the Employer's business interests within the various geographical locations of their different offices.</p>

        <h4>9. Governing Law:</h4>
        <p>This agreement shall be governed by and construed in accordance with the laws of Belgium.</p>

        <h4>10. Entire Agreement:</h4>
        <p>This agreement constitutes the entire understanding between the parties and supersedes all prior agreements and understandings, whether written or oral.</p>

        <p>By signing below, the parties acknowledge and agree to the terms and conditions set forth in this agreement.</p>

        <p><i>$employee_name</i></p>

        <p><i>$company_name</i></p>
        ");
@endphp
