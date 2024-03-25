<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/manager.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Manager page</title>

    <style>
        .gantt-chart {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .holiday-row {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .employee-name {
            width: 120px;
        }
        .bar {
            background-color: #007bff;
            height: 20px;
        }
    </style>

</head>
<body style="background-color: #D6D5C9">
    <h1>Welcome manager</h1><br/>

    <div class="requests">
    @php
        use Carbon\Carbon;

        $manager_id = request()->input('manager_id');

        if(request('decline') == 1){
            $id = request('id');
            $request_id = request('req_id');
            DB::update("update holidays set is_active = 0 where id = $request_id and employee_profile_id = $id");
        }
        if(request('accept') == 1){
            $id = request('id');
            $request_id = request('req_id');
            DB::update("update holidays set manager_approval = 1, boss_approval = 1, is_active = 0 where id = $request_id and employee_profile_id = $id");
        }
    
        // Selecting all holiday requests and sorting them by request date in descending order
        //$requests = DB::select("select * from holidays where is_active = 1 order by request_date desc");
        
        
        //selecting the holidays requests based on which employees that are under the manager
        $manager_user = DB::select("select * from users where id = $manager_id");
        echo("<h4>Employees managed by " . $manager_user[0]->first_name . "</h4>");

        //$employee_manager_relation = DB::select("SELECT * FROM users INNER JOIN team_members ON team_members.user_id = users.id WHERE team_members.is_manager = 0");
        $team_members = [];

        $manager_team = DB::select("select team_id from team_members where user_id = $manager_id");
        $employee_manager_relation = DB::select("select * from team_members where team_id = " . $manager_team[0]->team_id. " and is_manager = 0");

        $all_requests = [];

        foreach ($employee_manager_relation as $relation) {
            $team_members = DB::select("select * from users where id = $relation->user_id");
            $emp_profile_id = $team_members[0]->employee_profile_id;

            // Fetch holidays for the current employee
            $requests = DB::select("SELECT * FROM holidays WHERE employee_profile_id = $emp_profile_id AND is_active = 1");
            
            // Append the requests for the current employee to the array
            $all_requests = array_merge($all_requests, $requests);

            // Select the employee profiles under this manager
            $employees = DB::select("select * from users where employee_profile_id = $emp_profile_id");
            
            // Output the employee names
            if(!empty($employees)){
                echo("<table style=\"width: 40%\">");
                echo("<th>EmployeeID</th><th>Name</th>");
                foreach ($employees as $employee) {
                    echo("<tr>");
                        echo("<td>" . $employee->employee_profile_id . "</td>");
                        echo("<td>" . $employee->first_name . " " . $employee->last_name . "</td>");
                    echo("</tr>");
                }
            }
            else{
                echo("<h2>You do not have any employees under your management</h2>");
                break;
            }
            echo("</table>");
        }

        echo("<br/><br/>");

        if(!empty($all_requests)){
            echo("<h3>Holiday requests</h3><br/>");
            echo("<table><th>Request date</th><th>Employee name</th><th>Start date</th><th>End date</th><th>Holiday type</th><th>Actions</th>");
            foreach ($all_requests as $request) {
                // Getting the name of the employee that made the request
                $employee_id = $request->employee_profile_id;
                $request_id = $request->id;
                $holiday_type = $request->holiday_type_id;

                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");
                $holiday_type_name = DB::select("select * from holiday_types where id = $holiday_type");


                echo("
                    <tr>
                        <td>$request->request_date</td>
                        <td>" . $fullname[0]->first_name . " " . $fullname[0]->last_name . "</td>
                        <td>$request->start_date</td>
                        <td>$request->end_date</td>
                        <td>" . $holiday_type_name[0]->type ."</td>
                        <td><a href=\"" . route('managerPage', ['accept' => 1, 'id' => $employee_id, 'req_id' => $request_id, 'manager_id' => $manager_id ]) . "\"><button class=\"accept\">Accept</button></a> 
                        <a href=\"" . route('managerPage', ['decline' => 1, 'id' => $employee_id, 'req_id' => $request_id, 'manager_id' => $manager_id ]) . "\"><button class=\"reject\">Decline</button></a> </td>
                    </tr>

                "); 
            }
            echo("</table><hr/>");

            echo("<br/><br/>");
            echo("<table style=\"width: 90%\">");
            }
            else{
                echo("<i>No pending requests at the moment</i>");
            }


            //Holiday overview in calendar view



            $previousMonth = null; // Variable to store the previous month
            $holidayTypeColors = [];
            $currentMonthHolidays = [];
            $legendDisplayed = false;

            $all_requests2 = [];

            foreach ($employee_manager_relation as $relation) {
                $team_members = DB::select("select * from users where id = $relation->user_id");

                // Fetch holidays for the current employee
                $requests = DB::select("select * from holidays where employee_profile_id = " . $team_members[0]->employee_profile_id . " and is_active = 0 and manager_approval = 1 order by start_date");
                
                // Append the requests for the current employee to the array
                $all_requests2 = array_merge($all_requests2, $requests);

                // Select the employee profiles under this manager
                $employees = DB::select("select * from users where employee_profile_id = " . $team_members[0]->employee_profile_id);
                
            }


            if (!empty($all_requests2)) {
                echo("<h2>Holiday overview</h2><br/>");
                // Initialize variables
                $previousMonth = null;
                $legendDisplayed = false;
                $holidayTypeColors = [];

                // Organize holidays by month
                $holidaysByMonth = [];
                foreach ($all_requests2 as $holiday) {
                    $startDate = Carbon::parse($holiday->start_date);
                    $fullMonthName = $startDate->format('F');
                    $year = $startDate->year;

                    $holidaysByMonth["$fullMonthName $year"][] = $holiday;
                }

                // Display holidays by month
                foreach ($holidaysByMonth as $monthYear => $holidays) {
                    list($fullMonthName, $year) = explode(' ', $monthYear);
                    echo("<h4>$fullMonthName $year</h4>");
                    echo("<table>");
                    echo('<tr>');
                    echo("<td>Name</td>");

                    $daysInMonth = Carbon::create($year, $startDate->month)->daysInMonth;
                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        echo($i < 10 ? "<th>0$i</th>" : "<th>$i</th>");
                    }
                    echo('</tr>');

                    foreach ($holidays as $holiday) {
                        $employee_id = $holiday->employee_profile_id;
                        $holidayTypeID = $holiday->holiday_type_id;
                        $startDate = Carbon::parse($holiday->start_date);
                        $endDate = Carbon::parse($holiday->end_date);
                        $startDay = $startDate->day;
                        $endDay = $endDate->day;
                        $days = $endDate->diffInDays($startDate);

                        $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");

                        echo('<tr>');
                        echo("<td>{$fullname[0]->first_name} {$fullname[0]->last_name}</td>");
                        for ($i = 1; $i <= $daysInMonth; $i++) {
                            if ($i >= $startDay && $i <= $endDay) {
                                // Generate a unique CSS class based on holiday type ID
                                $cssClass = "holiday_type_" . $holidayTypeID;
                                
                                // Check if color is assigned to this holiday type ID
                                if (isset($holidayTypeColors[$holidayTypeID])) {
                                    // Use the assigned color
                                    $backgroundColor = $holidayTypeColors[$holidayTypeID];
                                } else {
                                    // Generate a random color
                                    $backgroundColor = '#' . substr(md5(rand()), 0, 6);
                                    // Assign the random color to the holiday type
                                    $holidayTypeColors[$holidayTypeID] = $backgroundColor;
                                    // Log the assignment
                                }
                                
                                echo("<td class=\"$cssClass\" style=\"border: 1px solid black; background-color: $backgroundColor\"></td>");
                            } else {
                                echo("<td style=\"border: 1px solid black;\"></td>");
                            }
                        }

                        echo('</tr>');
                    }
                    echo('</table>');
                }

                // Display legend
                if (!$legendDisplayed) {
                    echo("<div class=\"legend\">");
                    echo('<h4>Legend:</h4>');
                    echo('<ul>');
                    foreach ($holidayTypeColors as $holidayTypeID => $color) {
                        $holidayType = DB::select("SELECT * FROM holiday_types WHERE id = $holidayTypeID");
                        $typeName = $holidayType[0]->type;
                        echo("<li style=\"color: $color;\">$typeName</li>");
                    }
                    echo('</ul>');
                    echo('</div>');
                    $legendDisplayed = true;
                }
            }

    @endphp

    </div> 

    </div>
</body>
</html>
