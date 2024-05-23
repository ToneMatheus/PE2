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

$meter_id = $_GET['ID'];

$sql = "SELECT index_values.id, index_values.reading_date, index_values.reading_value FROM index_values
RIGHT JOIN consumptions on consumptions.current_index_id = index_values.id
WHERE index_values.meter_id = $meter_id
ORDER BY consumptions.id DESC
LIMIT 1;";

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