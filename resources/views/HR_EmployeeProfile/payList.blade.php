<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/payList.css" rel="stylesheet" type="text/css"/>
    <link href="/css/header.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Payslip list</title>
</head>

<body class="body">
    <div class="container-trui">
        <div class="content">
            <div>
                <h1><u>Your payslips</u></h1>
                @php
                   $userID = Auth::id();

                    $payslipInfo = DB::select("select * from payslips where employee_profile_id = $userID");//fetching payslip plus contract information
                    $numRows = count($payslipInfo);

                    if(!empty($payslipInfo)){
                        echo("<hr/>");
                            foreach($payslipInfo as $info){
                                $id = htmlspecialchars($info->id);
                                $start = htmlspecialchars($info->start_date);
                                $end = htmlspecialchars($info->end_date);
                                $issued = htmlspecialchars($info->creation_date);
                                $hours = htmlspecialchars($info->total_hours);
                                $amountPerHour = htmlspecialchars($info->amount_per_hour);
                                $totalAmount = $hours * $amountPerHour;
                                $taxAmount = $totalAmount * 0.21;
                                $totalAmount = $totalAmount - $taxAmount;

                                echo("                       
                                <span style=\"margin-left: 85px\">Created on $issued </span><br/>
                                <a href=\"" . route('payslip', ['id' => $id]) . "\" class=\"salary\"> 
                                    <div class=\"data\">
                                        <div style=\"float: left;\">
                                            <div class=\"image\"><img src=\"/images/paycheck.png\" alt=\"paycheck\"/></div>
                                        </div>
                                        <span>Duration<br/>$start --> $end</span>        
                                        <b>$totalAmount$</b>
                                    </div>
                                </a>
                                <hr/>");
                            }
                        }
                    else{
                        echo("<h2 style=\"text-align: center\">You haven't had any payslips yet</h2>");
                    }
                @endphp
            </div>
        </div>
    </div>
</body>