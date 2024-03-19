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
<body>
    <h1>Welcome manager</h1><br/>

    <a href="{{route('employeeList')}}"><h5>Employee list</h5></a><br/>

    <h3>Holiday requests</h3>

    <div class="requests">
    @php
        use Carbon\Carbon;

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
        $requests = DB::select("select * from holidays where is_active = 1 order by request_date desc");

        if(!empty($requests)){
            echo("<table><th>Request date</th><th>Employee name</th><th>Start date</th><th>End date</th><th>Reason</th><th>Actions</th>");
            foreach ($requests as $request) {
                // Getting the name of the employee that made the request
                $employee_id = $request->employee_profile_id;
                $request_id = $request->id;

                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");

                echo("
                    <tr>
                        <td>$request->request_date</td>
                        <td>" . $fullname[0]->first_name . " " . $fullname[0]->last_name . "</td>
                        <td>$request->start_date</td>
                        <td>$request->end_date</td>
                        <td>$request->reason</td>
                        <td><a href=\"" . route('managerPage', ['accept' => 1, 'id' => $employee_id, 'req_id' => $request_id ]) . "\"><button class=\"accept\">Accept</button></a> 
                        <a href=\"" . route('managerPage', ['decline' => 1, 'id' => $employee_id, 'req_id' => $request_id ]) . "\"><button class=\"reject\">Decline</button></a> </td>
                    </tr>

                "); 
            }
            echo("</table><hr/>");

            echo("<h2>Holiday overview</h2>");
            echo("<table style=\"width: 90%\">");
            }


            //Holiday overview in calendar view



            $previousMonth = null; // Variable to store the previous month
            $holidayTypeColors = [];
            $currentMonthHolidays = [];
            $legendDisplayed = false;

            $requests = DB::select("select * from holidays where is_active = 0 and manager_approval = 1 order by request_date desc");

            if(!empty($requests)){
                foreach ($requests as $holiday) {
                //fetch the holiday type
                $employee_id = $holiday->employee_profile_id;
                $holidayTypeID = $holiday->holiday_type_id;

                // Check if color is already assigned to this holiday type
                if (!isset($holidayTypeColors[$holidayTypeID])) {
                    // Generate a random color
                    $randomColor = '#' . substr(md5(rand()), 0, 6);

                    // Assign the random color to the holiday type
                    $holidayTypeColors[$holidayTypeID] = $randomColor;
                }

                $startDate = Carbon::parse($holiday->start_date); // Accessing the property directly
                $endDate = Carbon::parse($holiday->end_date); // Accessing the property directly

                $startDay = $startDate->day;
                $month = $startDate->month;
                $year = $startDate->year;

                $endDay = $endDate->day;
                $days = $endDate->diffInDays($startDate);

                //to get the number of days in this month
                $daysInMonth = Carbon::create($year, $month)->daysInMonth;

                $fullMonthName = $startDate->format('F');

                // Display the month name if it's different from the previous month
                if ($fullMonthName != $previousMonth) {
                    // If this is not the first month, close the previous table
                    if ($previousMonth !== null) {
                        echo('</table>');
                    }

                    // Start a new table for the current month
                    echo("<h2>$fullMonthName $year</h2>");
                    echo('<table>');
                    echo('<tr>');
                    // Make the first <td> spanning multiple columns
                    echo("<td>Name</td>");
                    for ($i = 1; $i <= $daysInMonth; $i++) { // Start from 2 since the first <td> is spanning multiple columns
                        if ($i == 0) {
                            echo("<th></th>");
                        } elseif ($i < 10 && $i > 0) {
                            echo("<th>0$i</th>");
                        } else {
                            echo("<th>$i</th>");
                        }
                    }
                    echo('</tr>');
                    $currentMonthHolidays = []; // Reset for the new month
                    $previousMonth = $fullMonthName; // Update the previous month
                }

                //selecting the name of each employee
                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");

                // Add current holiday to the list of holidays for the month
                $currentMonthHolidays[] = [
                    'name' => $fullname[0]->first_name . " " . $fullname[0]->last_name,
                    'startDay' => $startDay,
                    'endDay' => $endDay,
                    'holidayTypeID' => $holidayTypeID
                ];
                
                if ($currentMonthHolidays) {
                    foreach ($currentMonthHolidays as $holidayInfo) {
                        echo('<tr>');
                        echo("<td>{$holidayInfo['name']}</td>");
                        for ($i = 1; $i <= $daysInMonth; $i++) {
                            if ($i >= $holidayInfo['startDay'] && $i <= $holidayInfo['endDay']) {
                                // Generate a unique CSS class based on holiday type ID
                                $cssClass = "holiday_type_" . $holidayInfo['holidayTypeID'];
                                echo("<td class=\"$cssClass\" style=\"border: 1px solid black; background-color: {$holidayTypeColors[$holidayInfo['holidayTypeID']]}\"></td>");
                            } else {
                                echo("<td style=\"border: 1px solid black;\"></td>");
                            }
                        }
                        echo('</tr>');
                    }
                }
            }

            // Display the legend box
            if (!$legendDisplayed) {
                echo('<tr><td colspan="' . ($daysInMonth + 1) . '">');
                echo('<h4>Legend:</h4>');
                echo('<ul>');
                foreach ($holidayTypeColors as $holidayTypeID => $color) {
                    // Fetch holiday type details from the database
                    $holidayType = DB::select("SELECT * FROM holiday_types WHERE id = $holidayTypeID");
                    $typeName = $holidayType[0]->type;
                    echo("<li style=\"color: $color;\">$typeName</li>");
                }
                echo('</ul>');
                echo('</td></tr>');
                $legendDisplayed = true;
            }

            // Close the last table if there are remaining holidays
            if ($previousMonth !== null) {
                echo('</table>');
            }

            }
    @endphp

    </div> 

    </div>
</body>
</html>

<!--echo("<div class=\"holiday-row\">
    <div class=\"employee-name\">" . $fullname[0]->first_name . "</div>
    <div class=\"bar\" style=\"width: {{ 3 * 20 }}px;\"></div>
    </div>");-->