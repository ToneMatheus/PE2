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

@include('layouts.header')

@yield('header')

<body class="body">
    <div class="container-trui">
        <div class="content">
            <div>
                <h1><u>Your payslips</u></h1>

                <span style="margin-left: 85px">Created on 03/02/2024</span><br/>
                <a href="{{route('payslip')}}" class="salary">
                    <div class="data">
                        <div style="float: left;">
                            <div class="image"><img src="/images/paycheck.png" alt="paycheck"/></div>
                        </div>
                        <span>Payslip January<br/>01/01/2024 - 31/01/2021</span>        
                        <b>1,545.31$</b>
                    </div>
                </a>
                <hr/>

                <!--second instance for demostration purposes-->
                <span style="margin-left: 85px">Created on 03/02/2024</span><br/>
                <a href="{{route('payslip')}}" class="salary">
                    <div class="data">
                        <div style="float: left;">
                            <div class="image"><img src="/images/paycheck.png" alt="paycheck"/></div>
                        </div>
                        <span>Payslip January<br/>01/01/2024 - 31/01/2021</span>        
                        <b>1,545.31$</b>
                    </div>
                </a>
                <hr/>

                <!--third instance for demostration purposes-->
                <span style="margin-left: 85px">Created on 03/02/2024</span><br/>
                <a href="{{route('payslip')}}" class="salary"> 
                    <div class="data">
                        <div style="float: left;">
                            <div class="image"><img src="/images/paycheck.png" alt="paycheck"/></div>
                        </div>
                        <span>Payslip January<br/>01/01/2024 - 31/01/2021</span>        
                        <b>1,545.31$</b>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>