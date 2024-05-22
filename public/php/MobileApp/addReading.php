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
$value = $_GET['value'];
$date = date('Y-m-d');

$sql = "INSERT INTO index_values (reading_date, reading_value ,meter_id)
VALUES ('$date', $value , $id)";

if ($con->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }

mysqli_close($con)

?>