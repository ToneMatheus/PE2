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
</head>

@php use Carbon\Carbon; @endphp

<x-app-layout>
<body style="background-color: #D6D5C9">
    
    <h1 style="text-align: center; margin-top: 20px" class="h1">Team management</h1><br/>

    <div class="requests" style="margin: auto">
        <div>
        <h4 style="margin-left: 20px" class="h4">My team</h4>
        </div>

        <div style="margin-bottom: 60px;" class="c">
            <div style="display: flex; justify-content: space-between">
                <div class="col-4">
                    <table class="employee-table">
                        <th>EmployeeID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Hire date</th>
                        <th>View profile</th>
                        @if (count($employee_manager_relation) > 0)
                            @foreach ($employee_manager_relation as $relation)
                                @php
                                    $team_members = DB::select("select employee_profile_id from users where id = $relation->user_id");
                                    $emp_profile_id = $team_members[0]->employee_profile_id;
                                    $employees = DB::select("select * from users where employee_profile_id = $emp_profile_id");
    
                                    $employee_profile = DB::select("select * from employee_profiles where id = $emp_profile_id");
                                @endphp
    
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->employee_profile_id }}</td>
                                        <td>{{ $employee->first_name }}</td>
                                        <td>{{ $employee->last_name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee_profile[0]->hire_date }}</td>
                                        <td style="text-align: center"><a href="{{ route('profile', ['id' => $relation->user_id]) }}"><button class="btn btn-primary">View</button></a></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <h2>You do not have any employees under your management</h2>
                        @endif
                    </table>
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
                
                {{-- <div class="container"> --}}
                    <h4>Team status</h4>
                    <div id="chart">
                        <canvas id="employeePieChart" width="270" height="270"></canvas>
                    </div>
                {{-- </div> --}}
    
            </div>

            @if (!empty($all_requests))
            @php $i = 1; @endphp
                <div class="col-8">
                    <h3 class="h4">Holiday requests</h3>
                    <table>
                        <th>#</th>
                        <th>Request date</th>
                        <th>Employee name</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Days</th>
                        <th>Holiday type</th>
                        <th>Status</th>
                        <th>Actions</th>
                        
                        @foreach ($all_requests as $request)
                            @php
                                $employee_id = $request->employee_profile_id;
                                $request_id = $request->id;
                                $holiday_type = $request->holiday_type_id;
                                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");
                                $holiday_type_name = DB::select("select * from holiday_types where id = $holiday_type");
                                $startDate = Carbon::parse($request->start_date);
                                $endDate = Carbon::parse($request->end_date);
                                $diffInDays = $endDate->diffInDays($startDate) + 1;
                            @endphp

                            @if ($request->reason == "Sick")
                            <tr style="background-color: pink">
                                <td>{{ $i }}</td>
                                <td>{{ $request->request_date }}</td>
                                <td>{{ $fullname[0]->first_name }} {{ $fullname[0]->last_name }}</td>
                                <td>{{ $request->start_date }}</td>
                                <td>{{ $request->end_date }}</td>
                                <td>{{ $diffInDays }}</td>
                                <td>{{ $holiday_type_name[0]->type }}</td>
                                <td><span class="badge badge-primary" style="font-size: 16px">Pending</span></td>
                                <td>
                                    <a href="{{ route('managerPage', ['accept' => 1, 'id' => $employee_id, 'req_id' => $request_id, 'manager_id' => $manager_id ]) }}"><button class="btn btn-warning">Demand documents</button></a>
                                    {{-- <a href="{{ route('managerPage', ['decline' => 1, 'id' => $employee_id, 'req_id' => $request_id, 'manager_id' => $manager_id ]) }}"><button class="btn btn-danger">Decline</button></a> --}}
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $request->request_date }}</td>
                                <td>{{ $fullname[0]->first_name }} {{ $fullname[0]->last_name }}</td>
                                <td>{{ $request->start_date }}</td>
                                <td>{{ $request->end_date }}</td>
                                <td>{{ $diffInDays }}</td>
                                <td>{{ $holiday_type_name[0]->type }}</td>
                                <td><span class="badge badge-primary" style="font-size: 16px">Pending</span></td>
                                <td>
                                    <a href="{{ route('managerPage', ['accept' => 1, 'id' => $employee_id, 'req_id' => $request_id, 'manager_id' => $manager_id ]) }}"><button class="btn btn-success">Accept</button></a>
                                    <a href="{{ route('managerPage', ['decline' => 1, 'id' => $employee_id, 'req_id' => $request_id, 'manager_id' => $manager_id ]) }}"><button class="btn btn-danger">Decline</button></a>
                                </td>
                            </tr>
                            @endif

                            @php $i++; @endphp
                        @endforeach
                    </table>
                    <hr />
                </div>
            @else
                <div class="col-8">
                    <i>No pending requests at the moment</i>
                </div>
            @endif
        </div>

        <div style="margin: auto">
            @if(!empty($all_data))
            <h2 style="text-align: center; margin-bottom: 20px" class="h4">Request history</h2>

            <table>
                <th>#</th>
                <th>Request date</th>
                <th>Emp name</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Requested days</th>
                <th>Holiday type</th>
                <th>Status</th>
                @php $i = 1; @endphp

                @foreach($all_data as $data)

                    @if($data->is_active == 0 && $data->manager_approval == 0 && $data->boss_approval == 0)
                        @php
                            $employee_id = $data->employee_profile_id;
                            $request_id = $data->id;
                            $holiday_type = $data->holiday_type_id;
                            $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");
                            $holiday_type_name = DB::select("select * from holiday_types where id = $holiday_type");
                            $startDate = Carbon::parse($data->start_date);
                            $endDate = Carbon::parse($data->end_date);
                            $diffInDays = $endDate->diffInDays($startDate) + 1;
                        @endphp
        
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $data->request_date }}</td>
                            <td>{{ $fullname[0]->first_name }} {{ $fullname[0]->last_name }}</td>
                            <td>{{ $data->start_date }}</td>
                            <td>{{ $data->end_date }}</td>
                            <td>{{ $diffInDays }}</td>
                            <td>{{ $holiday_type_name[0]->type }}</td>
                            <td><span class="badge badge-danger" style="font-size: 16px">Rejected</span></td>
                        </tr>
                        @php $i++; @endphp

                    @elseif($data->is_active == 0 && $data->manager_approval == 1 && $data->boss_approval == 1)
                            @php
                                $employee_id = $data->employee_profile_id;
                                $request_id = $data->id;
                                $holiday_type = $data->holiday_type_id;
                                $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");
                                $holiday_type_name = DB::select("select * from holiday_types where id = $holiday_type");
                                $startDate = Carbon::parse($data->start_date);
                                $endDate = Carbon::parse($data->end_date);
                                $diffInDays = $endDate->diffInDays($startDate) + 1;
                            @endphp
            
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data->request_date }}</td>
                                <td>{{ $fullname[0]->first_name }} {{ $fullname[0]->last_name }}</td>
                                <td>{{ $data->start_date }}</td>
                                <td>{{ $data->end_date }}</td>
                                <td>{{ $diffInDays }}</td>
                                <td>{{ $holiday_type_name[0]->type }}</td>
                            <td><span class="badge badge-success" style="font-size: 16px">Approved</span></td>
                        </tr>
                        @php $i++; @endphp
                    @endif
                @endforeach
            @endif

            </table>
        </div>
    </div> 

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
                }
            });
        }
        // Call the function to initially render the pie chart with PHP data
        var phpEmployeeData = {!! json_encode($employee_data) !!};
        updatePieChart(phpEmployeeData);
        </script>
</body>
</html>
</x-app-layout>