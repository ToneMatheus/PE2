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
                <h3>Your addresses to visit today:</h3>
                <table class="scheduleTable">
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Address</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Lydia Peeters</td>
                        <td>Jan Pieter de Nayerlaan 5, Sint-Katelijne-Waver</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Aryan Van der Ven</td>
                        <td>Koning Albertplein 2, Mechelen</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>John Doe</td>
                        <td>Nekkerspoelstraat 285, Mechelen</td>
                    </tr>
                </table>
            </div>
            <div class="mapContainer">
                <!-- <gmp-map center="48.8583984375,2.2944705486297607" zoom="14" map-id="DEMO_MAP_ID">
                <gmp-advanced-marker position="48.8583984375,2.2944705486297607" title="My location"></gmp-advanced-marker>
                </gmp-map> -->
                <iframe class="map" src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyByBdD-HWq4mvd5hh2A_4HsIV3kBpp2HiI
                &amp;origin=Koning+Albertplein+2+Mechelen&amp;destination=Jan+Pieter+de+Nayerlaan+5&amp;waypoints=Nekkerspoelstraat+285+Mechelen&amp;avoid=tolls|highways" allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</body>
</html>