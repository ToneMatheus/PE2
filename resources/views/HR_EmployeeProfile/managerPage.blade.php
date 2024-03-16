<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/manager.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Manager page</title>
</head>
<body>
    <h1>Welcome manager</h1><br/>

    <a href="{{route('employeeList')}}"><h5>Employee list</h5></a><br/>

    <h3>Holiday requests</h3>

    <div class="requests">
    @php
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
            foreach ($requests as $request) {
                // Getting the name of the employee that made the request
                $employee_id = $request->employee_profile_id;
                $request_id = $request->id;

                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");

                echo("
                    <b>Holiday demanded by:</b> " . $fullname[0]->first_name . " " . $fullname[0]->last_name . "<br/>
                    <b>On the:</b> $request->request_date<br/>
                    <b>From:</b> $request->start_date <b>To:</b> $request->end_date<br/>
                    <b>Reason for request:</b> $request->reason<br/>
                    <a href=\"" . route('managerPage', ['accept' => 1, 'id' => $employee_id, 'req_id' => $request_id ]) . "\"><button class=\"accept\">Accept</button></a> 
                    <a href=\"" . route('managerPage', ['decline' => 1, 'id' => $employee_id, 'req_id' => $request_id ]) . "\"><button class=\"reject\">Decline</button></a> 
                    <hr/>
                ");
            }
        } else {
            echo("<p><i>There are no requests at the moment</i></p>");
        }
    @endphp
    </div>        
</body>
</html>