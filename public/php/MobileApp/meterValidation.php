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

$employeeID = $_GET['Employee'];

$sql = "";

$result=mysqli_query($con, $sql);
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[]=$row;
    }

    print(json_encode($data));
}

mysqli_close($con)

?>