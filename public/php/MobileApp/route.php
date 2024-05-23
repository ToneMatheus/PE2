<?php

$host='127.0.0.1';
$username='root';
$pwd='';
$db='energy_supplier';
$waypoints = '';
$company_address = urlencode('Jan Pieter de Nayerlaan 7 Sint Katelijne Waver');
$optimized_waypoints = '';


$con=mysqli_connect($host,$username,$pwd,$db) or die('Unable to connect');

if(mysqli_connect_error())
{
    echo "Failed to connect to Database ".mysqli_connect_error();
}

$employeeID = $_GET['Employee'];
$priority = $_GET['Priority'];
$date = date('Y-m-d');

//$sql="SELECT * FROM meters";
$sql = "SELECT t1.first_name, t1.last_name, t1.street, t1.number, t1.postal_code, t1.city, t1.EAN, t1.type, t1.meter_id, t1.priority, t1.employee_profile_id, COALESCE(t2.reading_date, 0) AS reading_date, COALESCE(t2.latest_reading_value, 0) AS latest_reading_value, t1.estimation_total as estimation FROM 
(SELECT users.first_name, users.last_name, addresses.street, addresses.number, addresses.postal_code, addresses.city, meters.EAN, meters.type, meters.id AS meter_id, meter_reader_schedules.priority, meter_reader_schedules.employee_profile_id, estimations.estimation_total FROM `users`
JOIN customer_addresses on users.id = customer_addresses.user_id
JOIN addresses on customer_addresses.address_id = addresses.id
JOIN meter_addresses on addresses.id = meter_addresses.address_id
JOIN meters on meter_addresses.meter_id = meters.id
JOIN meter_reader_schedules on meters.id = meter_reader_schedules.meter_id
JOIN estimations on estimations.meter_id = meter_reader_schedules.meter_id
WHERE meter_reader_schedules.reading_date = '$date' AND meter_reader_schedules.employee_profile_id = $employeeID AND meter_reader_schedules.status = 'unread' AND meter_reader_schedules.priority >= $priority) AS t1
LEFT JOIN
(SELECT index_values.meter_id, index_values.reading_date, index_values.reading_value AS latest_reading_value
FROM index_values
WHERE (index_values.meter_id, index_values.reading_value) IN (
    SELECT index_values.meter_id, MAX(index_values.reading_value) FROM index_values
    GROUP BY index_values.meter_id
)) AS t2 ON t1.meter_id = t2.meter_id;";

$result=mysqli_query($con, $sql);
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[]=$row;
        $address=$row['street'].' '.$row['number'].' '.$row['city'];
        $addresses[] = $address;
        $waypoints .= urlencode($address.'|');
    }
}

mysqli_close($con);

$waypoints = substr($waypoints, 0, -3); // removing the pipe character in the end

$api = 'https://maps.googleapis.com/maps/api/directions/json?destination=' . $company_address . '&origin=' . $company_address . '&waypoints=optimize%3Atrue%7C' . $waypoints . "&key=AIzaSyDJxVIJtLGU0anxCft7GRMVblVKBByiTj8";

// API endpoint URL
$url = $api;

// Initialize curl
$curl = curl_init();

// Set curl options
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


// Execute curl and get response
$response = curl_exec($curl);

// Check for errors
if(curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
    // Response received successfully
    // Do something with the response
    $resultdata =  json_decode($response, true);
    $order = $resultdata['routes'][0]['waypoint_order'];

    foreach($order as $position){
        $optimized_data[] = $data[$position];
    }

    print json_encode($optimized_data);
}

// Close curl
curl_close($curl);

// $data = json_decode($response, true);
// $waypoint_order = $data['routes'][0]['waypoint_order']; // optimized order of addresses

// foreach($waypoint_order as $position) {
//     $optimized_waypoints .= urlencode($addresses[$position]) . "|";
// }

// $optimized_waypoints = substr($optimized_waypoints, 0, -1); // removing the pipe character in the end

// $url = "https://www.google.com/maps/embed/v1/directions?origin=" . $company_address .
//         "&destination=" . $company_address .
//         "&waypoints=" . $optimized_waypoints . "&avoid=highways&key=AIzaSyDJxVIJtLGU0anxCft7GRMVblVKBByiTj8"; // creating url for maps embed
// return $optimized_waypoints;
?>