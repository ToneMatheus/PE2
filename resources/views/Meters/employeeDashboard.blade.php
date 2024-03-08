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
            <h1>Good morning, Employee</h1>
        </div>
        <div class="schedule">
            <div class="scheduleTableDiv">
                <table class="scheduleTable">
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Address</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>Jan Pieter de Nayerlaan 5</td>
                    </tr>
                </table>
            </div>
            <div class="map">
                <!-- <gmp-map center="48.8583984375,2.2944705486297607" zoom="14" map-id="DEMO_MAP_ID">
                <gmp-advanced-marker position="48.8583984375,2.2944705486297607" title="My location"></gmp-advanced-marker>
                </gmp-map> -->
                <iframe class="map-top" width="598" height="450" src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyDJxVIJtLGU0anxCft7GRMVblVKBByiTj8
    &amp;origin=De+Bruul+45+Mechelen&amp;destination=Jan+Pieter+de+Nayerlaan+5&amp;avoid=tolls|highways" allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</body>
</html>