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
                    $userID = 1;//To be replaced by the real ID!

                    $payslipInfo = DB::select("select * from payslips where employeeID = $userID");//fetching payslip plus contract information
                    $numRows = count($payslipInfo);

                    if(!empty($payslipInfo)){
                        echo("<hr/>");
                            foreach($payslipInfo as $info){
                                $id = htmlspecialchars($info->ID);
                                $start = htmlspecialchars($info->startDate);
                                $end = htmlspecialchars($info->endDate);
                                $issued = htmlspecialchars($info->creationDate);
                                $hours = htmlspecialchars($info->totalHours);
                                $amountPerHour = htmlspecialchars($info->amountPerHour);
                                $totalAmount = $hours * $amountPerHour;

                                echo("                       
                                <span style=\"margin-left: 85px\">Created on $issued </span><br/>
                                <a href=\"" . route('payslip', ['id' => $id]) . "\" class=\"salary\"> 
                                    <div class=\"data\">
                                        <div style=\"float: left;\">
                                            <div class=\"image\"><img src=\"/images/paycheck.png\" alt=\"paycheck\"/></div>
                                        </div>
                                        <span>Duration<br/>$start - $end</span>        
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