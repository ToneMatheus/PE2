<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/manager.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Manager page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        #chart {
            float: left;
        }
        .requests {
            /* background-color: white; */
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .holiday-overview {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .employee-table th {
            width: 120px;
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
        echo("<div><h4>Employees managed by " . $manager_user[0]->first_name . "</h4>");

        //$employee_manager_relation = DB::select("SELECT * FROM users INNER JOIN team_members ON team_members.user_id = users.id WHERE team_members.is_manager = 0");
        $team_members = [];

        $manager_team = DB::select("select team_id from team_members where user_id = $manager_id");
        $employee_manager_relation = DB::select("select * from team_members where team_id = " . $manager_team[0]->team_id. " and is_manager = 0");

        $all_requests = [];

        echo("<div style=\"margin-bottom: 60px;\" class=\"c\">");
        echo("<div class=\"col-4\">");
        echo("<table class=\"employee-table\"><th>EmployeeID</th><th>Name</th>");

        $number_employees = 0;

        foreach ($employee_manager_relation as $relation) {
            $team_members = DB::select("select employee_profile_id from users where id = $relation->user_id");
            $emp_profile_id = $team_members[0]->employee_profile_id;

            // Fetch holidays for the current employee
            $requests = DB::select("SELECT * FROM holidays WHERE employee_profile_id = $emp_profile_id AND is_active = 1");
            
            // Append the requests for the current employee to the array
            $all_requests = array_merge($all_requests, $requests);

            // Select the employee profiles under this manager
            $employees = DB::select("select * from users where employee_profile_id = $emp_profile_id");
            
            
            // Output the employee names
            if (!empty($employees)) {
                foreach ($employees as $employee) {
                    $number_employees++;
                    echo("<tr>");
                    echo("<td>" . $employee->employee_profile_id . "</td>");
                    echo("<td>" . $employee->first_name . " " . $employee->last_name . "</td>");
                    echo("</tr>");
                }
            } else {
                echo("<h2>You do not have any employees under your management</h2>");
                break;
            }
        }

        echo("</table>");
        echo("</div>");

        if(!empty($all_requests)){
            echo("<div class=\"col-8\">");
            echo("<h3>Holiday requests</h3><br/>");
            echo("<table><th>Request date</th><th>Employee name</th><th>Start date</th><th>End date</th><th>Number of days</th><th>Holiday type</th><th>Actions</th>");
            foreach ($all_requests as $request) {
                // Getting the name of the employee that made the request
                $employee_id = $request->employee_profile_id;
                $request_id = $request->id;
                $holiday_type = $request->holiday_type_id;

                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");
                $holiday_type_name = DB::select("select * from holiday_types where id = $holiday_type");

                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);

                // Calculate the difference in days
                $diffInDays = $endDate->diffInDays($startDate);
                $diffInDays += 1;

                

                echo("
                    <tr>
                        <td>$request->request_date</td>
                        <td>" . $fullname[0]->first_name . " " . $fullname[0]->last_name . "</td>
                        <td>$request->start_date</td>
                        <td>$request->end_date</td>
                        <td>$diffInDays</td>
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
            echo("</div>");
            echo("</div>");

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
            }


            if (!empty($all_requests2)) {
                echo("<div class=\"holiday-overview c\" style=\"margin: auto\"><h2>Holiday overview</h2><br/>");
                // Initialize variables
                $previousMonth = null;
                $legendDisplayed = false;
                $holidayTypeColors = [];

                // Organize holidays by month
                $holidaysByMonth = [];
                foreach ($all_requests2 as $holiday) {
                    $startDate = Carbon::parse($holiday->start_date);
                    $fullMonthName = $startDate->format('F Y');

                    $holidaysByMonth[$fullMonthName][] = $holiday;
                }

                // Display holidays by month in chronological order
                ksort($holidaysByMonth);
                foreach ($holidaysByMonth as $monthYear => $holidays) {
                    echo("<h3 style=\"margin-top: 30px; display: block\">$monthYear</h3>");
                    
                    echo("<table style=\"float: left\">");
                    echo('<tr>');
                    echo("<td>Name</td>");

                    $startDate = Carbon::parse($holidays[0]->start_date);
                    $daysInMonth = $startDate->daysInMonth;
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
                echo("</div>");
            }

    @endphp

    </div> 

    </div>

    @php
    $employees_on_holidays = count($all_requests2);
    $present_employees = $number_employees - count($all_requests2);
    
        $employee_data = [
            ["name" => "Employees on holiday", "attendance" => $employees_on_holidays],
            ["name" => "Present employees", "attendance" => $present_employees],
        ];

        // Convert the array to JSON format
        $employee_json = json_encode($employee_data);
    @endphp

    <div id="chart">
        <canvas id="employeePieChart" width="200" height="200"></canvas>
    </div>

    <script>
        // Function to dynamically update the pie chart
        function updatePieChart(employees) {
            // Get attendance data from employees
            var labels = [];
            var data = [];
            employees.forEach(function(employee) {
                labels.push(employee.name);
                data.push(employee.attendance);
            });

            // Get the canvas element
            var ctx = document.getElementById('employeePieChart').getContext('2d');

            // Create the pie chart
            var employeePieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            // Add more colors as needed
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            // Add more colors as needed
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: false, // Stop rotation animation
                        animateScale: false // Stop scale animation
                    }
                }
            });
        }

        // Call the function to initially render the pie chart with PHP data
        // Call the function to initially render the pie chart with PHP data
        var phpEmployeeData = {!! json_encode($employee_data) !!};
        updatePieChart(phpEmployeeData);
        </script>
</body>
</html>