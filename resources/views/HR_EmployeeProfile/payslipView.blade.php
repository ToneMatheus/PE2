@php
    $userID = 1;//To be replaced by the real ID!
    $id = $_GET['id'];

    $payslipInfo = DB::select("select * from payslips where employeeID = $userID and ID = $id");//fetching payslip plus contract information
    $numRows = count($payslipInfo);

    foreach($payslipInfo as $info){
        $id = htmlspecialchars($info->ID);
        $start = htmlspecialchars($info->startDate);
        $end = htmlspecialchars($info->endDate);
        $issued = htmlspecialchars($info->creationDate);
        $hours = htmlspecialchars($info->totalHours);
        $amountPerHour = htmlspecialchars($info->amountPerHour);
        $totalAmount = $hours * $amountPerHour;
    }

    $users = DB::select("select * from employee where ID = $userID");//selecting employee information

    foreach ($users as $user) {
        $lastName = htmlspecialchars($user->lastName);
        $firstName = htmlspecialchars($user->firstName);
        $job = htmlspecialchars($user->job);
        $addressID = htmlspecialchars($user->addressID);
      
        //fetching user address from the db
        $address = DB::select("select * from address where ID = $addressID");
        foreach ($address as $add){
          $street = htmlspecialchars($add->street);
          $num = htmlspecialchars($add->number);
          $pC = htmlspecialchars($add->postalCode);
          $bus = htmlspecialchars($add->bus);
          $city = htmlspecialchars($add->city);
          $region = htmlspecialchars($add->region);

          $userAddress = "" . $street . " " . $num . ", " . $pC . " " . $city . ". " . $region . ".";//joining the address into one long address
        }

    }


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
                    $street, $num $bus<br/>
                    $pC $city, $region
                </div>

                <div style=\"padding-left: 20px; padding-top: 20px\">
                    <b><u>Contract details:</u></b><br/>
                    <b>Job:</b> maintainance manager<br/>
                    <b>Department:</b> Tech/IT
                </div>
            </div>
        </div>

        <table>
            <tr>
                <th>Amount per hour: 16.12$</th>
            </tr>

            <tr class=\"col-name\">
                <th>Description</th><th>Days</th><th>Hours</th><th style=\"text-align: center\">Amount</th>
            </tr>

            <tr>
                <td>Days worked - brut</td>
                <td>20</td>
                <td>100,5</td>
                <td style=\"text-align: center\"><b>1606,60$</b></td>
            </tr>

            <tr>
                <td>Holidays taken</td>
                <td>0</td>
                <td>/</td>
                <td style=\"text-align: center\">
                    Number of days taken * 50
                </td>
            </tr>

            
            <tr>
                <td colspan=\"3\">Medical allowance</td>
                <td style=\"text-align: center\">300$</td>
            </tr>

            <tr>
                <td colspan=\"3\">TVA</td>
                <td style=\"text-align: center\">
                    1956,09 * 0.21<br/>
                    410.78
                </td>
            </tr>

            <tr>
                <td colspan=\"3\">Salary - net</td>
                <td style=\"text-align: center\">1956,09 - 410.78<br/><b>1,545.31$</b></td>
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
                <td colspan=\"3\" style=\"text-align: right;\"><b>Sent to the account BE23 2341 1234 2523 Name: John Doe</b></td>
                <td style=\"text-align: center\"><b>1,545.31$</b></td>
            </tr>
        </table>
    ");
@endphp