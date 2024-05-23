<?php

$host='127.0.0.1';
$username='root';
$pwd='';
$db='energy_supplier';

$con=mysqli_connect($host,$username,$pwd,$db) or die('Unable to connect');

if(mysqli_connect_error())
{
    echo "Failed to connect to Database ".mysqli_connect_error();
}

$id = $_GET['ID'];

$datetime = new DateTime('tomorrow');
$Date = $datetime->format('Y-m-d');

$sql = "UPDATE `meter_reader_schedules` SET `reading_date` = '$Date', `priority` = 1 WHERE `meter_reader_schedules`.`meter_id` = $id;";

if ($con->query($sql) === TRUE) {
    echo "Meter updated";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }

mysqli_close($con)

?>