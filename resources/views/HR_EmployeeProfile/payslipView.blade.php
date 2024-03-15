@php
    use Carbon\Carbon;

    $userID = 1;//To be replaced by the real ID!
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
        $addressID = htmlspecialchars($user->address_id);
      
        //fetching user address from the db
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

    echo("
        <div class=\"general-details\">
            <div style=\"border-right: 2px solid black;\">
                <div style=\"border-bottom: 2px solid black; padding-right: 300px; padding-bottom: 20px\">
                    <b>Energy Company</b><br/>
                    Jan Pieter de Nayerlaan 5<br/>
                    2860 Sint-Katelijne-Waver<br/>
                    <b>EmployeeID:</b> $userID
                </div>

                <div style=\"padding-right: 300px; padding-top: 20px\">
                    <b>Billing date</b><br/>
                    <b>Work period:</b> From $start to $end<br/>
                    <b>Date calculated:</b> $issued
                </div>
            </div>
            
            <div>
                <div style=\"border-bottom: 2px solid black; padding-left: 20px; padding-bottom: 20px\">
                    <b>Name:</b> $firstName $lastName<br/>
                    $street, $num $box<br/>
                    $pC $city, $province
                </div>

                <div style=\"padding-left: 20px; padding-top: 20px\">
                    <b><u>Contract details:</u></b><br/>
                    <b>Job:</b> $job<br/>
                    <b>Department:</b> $dept
                </div>
            </div>
        </div>

        <table>
            <tr>
                <th>Amount per hour: $amountPerHour$</th>
            </tr>

            <tr class=\"col-name\">
                <th>Description</th><th>Days</th><th>Hours</th><th style=\"text-align: center\">Amount</th>
            </tr>

            <tr>
                <td>Days worked - brut</td>
                <td>$daysWorked</td>
                <td>$hours</td>
                <td style=\"text-align: center\"><b>$totalAmount$</b></td>
            </tr>

            <tr>
                <td>Holidays taken</td>
                <td>$differenceInDays</td>
                <td>/</td>
                <td style=\"text-align: center\">
                    $differenceInDays * $amountPerHour<br/>
                    $totalAmount - $newAmount = $newTotalAmount
                </td>
            </tr>

            <tr>
                <td colspan=\"3\">TVA</td>
                <td style=\"text-align: center\">
                    $newTotalAmount * 0.21 =
                    $TVA
                </td>
            </tr>

            <tr>
                <td colspan=\"3\">Salary - net</td>
                <td style=\"text-align: center\">$newTotalAmount$ - $TVA$<br/><b>$total$</b></td>
            </tr>

            <tr style=\"height: 170px\">
                <td colspan=\"3\"></td>
                <td></td>
            </tr>

            <tr>   
                <td colspan=\"3\"></td >
                <td class=\"total\"><b>Total</b></td>
            </tr>

            <tr>
                <td colspan=\"3\" style=\"text-align: right;\">Sent to the account <b>$IBAN</b> Name: <b>$firstName $lastName</b></td>
                <td style=\"text-align: center\"><b>$total$</b></td>
            </tr>
        </table>
    ");
@endphp