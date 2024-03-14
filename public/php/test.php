<?php
session_start();

$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'energy_supplier';

$link = mysqli_connect($host, $user, $password, $database) or die("Error: no connection can be made to the host");
mysqli_select_db($link, $database) or die("Error: the database could not be opened");


if (!isset($_SESSION['user'])) 
{
    $_SESSION['user'] = array();
}
else if(!isset($_SESSION['green']))
{
    $_SESSION['green'] = array();
}
else if(!isset($_SESSION['purple']))
{
    $_SESSION['purple'] = array();
}
else if(!isset($_SESSION['pink']))
{
    $_SESSION['pink'] = array();
}
else if(!isset($_SESSION['numCal']))
{
    $_SESSION['numCal'] = "";
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idNum'])) 
{
    $idNum = $_POST['idNum'];
    $_SESSION['numCal'] = $idNum;
    
    // Now you can use $idNum in your PHP code
    echo "Received idNum: " . $idNum;
}
else if(isset($_POST['green']))
{
    $color = $_POST['green'];
    echo "Received green: " . $color;
    $_SESSION['user']['green'] = $color;
    $color < 1 ?  $_SESSION['user']['green'] += 1 : $color = 1;
    echo "\n";
    echo $_SESSION['user']['green'], "test";
} 
else if(isset($_POST['purple']))
{
    $color = $_POST['purple'];
    echo "Received purple: " . $color;
    $_SESSION['user']['purple'] = $color;
    $color < 1 ?  $_SESSION['user']['purple'] += 1 : $color = 1;
    echo "\n";
    echo $_SESSION['user']['purple'];
} 
else if(isset($_POST['pink']))
{
    $color = $_POST['pink'];
    echo "Received pink: " . $color;
    $_SESSION['user']['pink'] = $color;
    $color < 1 ?  $_SESSION['user']['pink'] += 1 : $color = 1;
    echo "\n";
    echo $_SESSION['user']['pink'];
}
else if(isset($_POST['button']))
{
    
    if(isset($_SESSION['user']['green']))
    {
        //calandar day
        $day = strval($_SESSION['numCal']);
        $toDay = "2024/03/$day";
        //holiday_types
        echo 'yes green I guess';
        $type = "Vacation";
        
        // $query = "INSERT INTO holiday_types ('type') VALUES ('$type')";
        $query = "SELECT * FROM holiday_types";
        
        $result = $link->query($query) or die("Error: an error has occurred while executing the query.");
        if ($result) 
        {
            echo("\nAdd type successful!\n");
            while ($row = mysqli_fetch_array($result))
            {
                
                $typeDB = $row['type'];
                echo $typeDB;
            }
            echo $_SESSION['numCal'];
        }


            $employee_profile_id = mysqli_real_escape_string($link, 1);
            $start_date = mysqli_real_escape_string($link, $toDay);
            $end_date = mysqli_real_escape_string($link, $toDay);
            $holiday_type_id = mysqli_real_escape_string($link, 1);
            $reason = mysqli_real_escape_string($link, "");
            $fileLoc = mysqli_real_escape_string($link, "");
            $manager_approval = mysqli_real_escape_string($link, 0);
            $boss_approval = mysqli_real_escape_string($link, 0);
            $is_active = mysqli_real_escape_string($link, 1);

            $query2 = "INSERT INTO holidays (employee_profile_id, start_date, end_date, holiday_type_id, reason, fileLocation, manager_approval, boss_approval, is_active) VALUES ('$employee_profile_id', '$start_date', '$end_date', '$holiday_type_id', '$reason', '$fileLoc', '$manager_approval', '$boss_approval', '$is_active')";
            $result2 = $link->query($query2) or die("Error: an error has occurred while executing the query.");
        if($result2)
        {
            echo "\n\n works \n";
        }
    }
    else
    {
        echo 'nothing selected';
    }

    // $employee_profile_id = mysqli_real_escape_string($link, $dataArray[1]);
    // $start_date = mysqli_real_escape_string($link, $dataArray[2]);
    // $end_date = mysqli_real_escape_string($link, $dataArray[3]);
    // $holiday_type_id = mysqli_real_escape_string($link, $dataArray[4]);
    // $reason = mysqli_real_escape_string($link, $dataArray[5]);
    // $fileLoc = mysqli_real_escape_string($link, $dataArray[6]);
    // $manager_approval = mysqli_real_escape_string($link, $dataArray[7]);
    // $boss_approval = mysqli_real_escape_string($link, $dataArray[8]);
    // $is_active = mysqli_real_escape_string($link, $dataArray[9]);
 



    // $query2 = "INSERT INTO holidays (employee_profile_id, start_date, end_date, holiday_type_id, reason, fileLoc, manager_approval, boss_approval, is_active) VALUES ('$firstName', '$lastName', '$street', '$num', '$city', '$postalCode', '$email', $admin, $active, '$pass')";
    // $result2 = $link->query($query2) or die("Error: an error has occurred while executing the query.");
    // if ($result2) 
    // {
    //     echo("Add successful!</br>");
    // }
} 
else if(isset($_POST['cancel']))
{
    // Unset session variable
    unset($_SESSION['user']);

    // Destroy the session
    session_destroy();
}
else 
{
    echo "No data received.";
}

// Unset session variable
//unset($_SESSION['user']);

// Destroy the session
//session_destroy();
?>