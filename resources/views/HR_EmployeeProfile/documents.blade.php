<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="/css/contract.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Your documents</title>

    </head>

    <style>
        .card img{
            width: 60px;
            margin-right: 20px;
        }

        .card-body{
            font-size: 30px;
            display: flex;
            /* flex-direction: column; */
            /* justify-content: center; */
        }

        .card-body:hover{
            background-color: rgb(205, 203, 203);
        }

        .card a{
            text-decoration: none;
            color: black;
        }

        .h1{
            margin: 20px 0px;
        }
    </style>

    <body>
        <div class="container">
            <p class="h1" style="text-align: center; color: white">Your documents</p>

            <div class="card">
                <a href="{{ route('contract')}}">
                    <div class="card-body">
                        <img src="/images/contract.png" alt="contract image"/>
                        <span>Contract</span>
                    </div>
                </a>

                <a href="{{ route('employeeBenefits')}}">
                    <div class="card-body">
                        <img src="/images/benefits.png" alt="benefits image"/>
                        <span>Your benefits</span>
                    </div>
                </a>

                <a href="{{ route('payList')}}">
                    <div class="card-body">
                        <img src="/images/paycheck.png" alt="paycheck image"/>
                        <span>Payslips</span>
                    </div>
                </a>
            </div>
        </div>
    </body>
</x-app-layout>