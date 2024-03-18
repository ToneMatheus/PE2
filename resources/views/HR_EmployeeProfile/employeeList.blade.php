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

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            tr:nth-child(even) {
                background-color: white;
            }
        </style>
    </head>
    <body>
        <h2>Employee list</h2>
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
                if (!empty($holiday)) {
                    $startDate = $holiday[0]->start_date; // Accessing the property on the first element
                    $endDate = $holiday[0]->end_date;
                }

                echo("
                    <table>
                        <tr>
                    <td>$fullname</td>
                    <td><p class=\"bar\"></p></td>  
                </tr>
                    </table>

                    <div class=\"bar\"></div>
                ");
            }
        @endphp
    
    </body>
</html>
