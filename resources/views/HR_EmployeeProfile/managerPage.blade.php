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
            echo("</table>");

            echo("<table style=\"width: 90%\">");

                $previousMonth = null; // Variable to store the previous month
                foreach($requests as $holiday) {
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
                        echo("<h2>$fullMonthName $year</h2>");
                        $previousMonth = $fullMonthName; // Update the previous month
                    }

                    // Table for each month
                    echo("<table style=\"width: 100%\">");

                    // Loop to display the dates above the cells
                    echo("<tr>");
                    for($i = 0; $i <= $daysInMonth; $i++) {
                        if($i == 0) {
                            echo("<th></th>");
                        } else {
                            echo("<th>$i</th>");
                        }
                    }
                    echo("</tr>");

                    // New row for each holiday
                    echo("<tr>");
                    echo("<td>" . $fullname[0]->first_name . " " . $fullname[0]->last_name . "</td>");
                    for($i = 1; $i <= $daysInMonth; $i++) {
                        if ($i >= $startDay && $i <= $endDay) {
                            echo("<td style=\"border: 1px solid black; background-color: grey\"></td>");
                        } else {
                            echo("<td style=\"border: 1px solid black;\"></td>");
                        }
                    }
                    echo("</tr>");

                    echo("</table>");
                }

                if (empty($requests)) {
                    echo("<p><i>There are no requests at the moment</i></p>");
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