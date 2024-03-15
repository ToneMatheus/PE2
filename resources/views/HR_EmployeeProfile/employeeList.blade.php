<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="/css/employeeList.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Employee List</title>
        <style>
            .cardd {
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 15px;
                display: flex;
                justify-content: space-around
            }
            .label {
                font-weight: bold;
                margin-bottom: 5px;
            }
            .info {
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        @php
            use Carbon\Carbon;

            $employees = DB::select("select * from employee_profiles");
            
            foreach($employees as $employee){
                $id = $employee->id;

                $user = DB::select("select * from users where employee_profile_id = $id");
                
                $fullname = $user[0]->first_name . " " . $user[0]->last_name;

                //getting user age
                $birthDate = Carbon::parse($user[0]->birth_date);
                $currentDate = Carbon::now();
                $age = $currentDate->diffInYears($birthDate);

                $holiday = DB::select("select * from holidays where employee_profile_id = $id");

                echo("
                <div class=\"cardd\">
                    <div class=\"info\">
                        <div class=\"label\">Full Name:</div>
                        <div id=\"fullname\"> " . $fullname . "</div>
                    </div>
                    <div class=\"info\">
                        <div class=\"label\">Age:</div>
                        <div id=\"age\"> " . $age . "</div>
                    </div>
                    <div class=\"info\">
                        <div class=\"label\">Start Date:</div>
                        <div id=\"start-date\">2024-01-01</div>
                    </div>
                    <div class=\"info\">
                        <div class=\"label\">End Date:</div>
                        <div id=\"end-date\">2024-12-31</div>
                    </div>
                </div>
                ");
            }
        @endphp

    </body>
</html>