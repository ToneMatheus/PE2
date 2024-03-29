<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="/css/dashboard.css" rel="stylesheet" type="text/css"/>

    <!-- The callback parameter is required, so we use console.debug as a noop -->
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByBdD-HWq4mvd5hh2A_4HsIV3kBpp2HiI&callback=console.debug&libraries=maps,marker&v=beta">
    </script>
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
            </div>
            <div class="mapContainer">
                <iframe class="map" src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyByBdD-HWq4mvd5hh2A_4HsIV3kBpp2HiI
                &amp;origin=Koning+Albertplein+2+Mechelen&amp;destination=Jan+Pieter+de+Nayerlaan+5&amp;waypoints=Nekkerspoelstraat+285+Mechelen&amp;avoid=tolls|highways" allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</body>
</html>