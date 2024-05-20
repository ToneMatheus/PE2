<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="/css/employeeDashboard.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <div class="container">
        <div class="title">
            @foreach($employeeName as $employee)
                <h1>Good morning, {{$employee->first_name}}</h1>
            @endforeach
        </div>
        <div class="schedule">
            <div class="scheduleTableDiv">
                <h3>Your addresses to visit today:</h3>
                <table class="scheduleTable">
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Address</th>
                    </tr>

                    @foreach($results as $result)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $result->first_name.' '.$result->last_name }}</td>
                            <td>{{ $result->street.' '.$result->number.', '.$result->city  }}</td>
                        </tr>
                    @endforeach
                </table>
                <a class="redirect" href="/enter_index_employee">
                    <div>Go to index entry page</div></a>
            </div>
            <div class="mapContainer">
                <iframe class="map" src={{ $url }} allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</body>
</html>