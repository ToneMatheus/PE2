<?php

use Illuminate\Support\Arr;

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

if (!isset($_SESSION['numCal']) || !is_array($_SESSION['numCal'])) 
{
    $_SESSION['numCal'] = array();
}
else if (!isset($_SESSION['request'])) 
{
    $_SESSION['request'] = array();
}

$test = array();
$r = 0;
$t = 0;
$j = 0;
$temp = array();
$bl = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idNum'])) 
{
    $idNum = $_POST['idNum'];
    
    echo "Received idNum: " . $idNum;
}
else if(isset($_POST['green']) && isset($_POST['dayNum']))
{
    $color = $_POST['green'];
    echo "Received green: " . $color;
    $_SESSION['user']['green'] = $color;
    $color < 1 ?  $_SESSION['user']['green'] += 1 : $color = 1;
    echo "\n";

    // echo $_SESSION['user']['green'], "test";
    $idNum = $_POST['dayNum'];
    $_SESSION['numCal'][] = $idNum;
    
    echo "Days stored in session:\n";
    foreach ($_SESSION['numCal'] as $item) 
    {
        echo $item . "\n";
    }
} 
else if(isset($_POST['purple']) && isset($_POST['dayNum']))
{
    $color = $_POST['purple'];
    echo "Received purple: " . $color;
    $_SESSION['user']['purple'] = $color;
    $color < 1 ?  $_SESSION['user']['purple'] += 1 : $color = 1;
    echo "\n";
    echo $_SESSION['user']['purple'];
} 
else if(isset($_POST['pink']) && isset($_POST['dayNum']))
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
    // TODO: send the month (and maybe year) to the request
    if(isset($_SESSION['user']['green']))
    {
        $array =  $_SESSION['numCal'];
        sort($array); // Sort the array in ascending order

        for ($i = 0; $i < count($array); $i++)
        {
            if ($array[$i + 1] - $array[$i] > 1)
            {
                $bl = true;
            }
        }

        if (!$bl) 
        {
            $maxValue = max($_SESSION['numCal']);
            $minValue = min($_SESSION['numCal']);
            echo "\nmax: " . $maxValue;
            echo "\nmin: " . $minValue . "\n";

            $thsDay = "2024/03/$minValue";
            $scdDay = "2024/03/$maxValue";
            $todayRequest = date("Y/m/d");
            $type = "Vacation";

            $date_now = new DateTime();
            $date2    = new DateTime("03/$minValue/2024");
            // check if date is in the past
            if ($date_now > $date2) 
            {
                echo 'the date that you are asking is in the past.';
            }
            else
            {
                $employee_profile_id = mysqli_real_escape_string($link, 1);
                $request_date = mysqli_real_escape_string($link, $todayRequest);
                $start_date = mysqli_real_escape_string($link, $thsDay);
                $end_date = mysqli_real_escape_string($link, $scdDay);
                $holiday_type_id = mysqli_real_escape_string($link, 1);
                $reason = mysqli_real_escape_string($link, "Vacation");
                $fileLoc = mysqli_real_escape_string($link, "");
                $manager_approval = mysqli_real_escape_string($link, 0);
                $boss_approval = mysqli_real_escape_string($link, 0);
                $is_active = mysqli_real_escape_string($link, 1);
    
                $check_table = "SELECT * FROM holidays WHERE employee_profile_id = $employee_profile_id";
                $check_table_result = $link->query($check_table) or die("Error: an error has occurred while executing the query.");
                $check = mysqli_fetch_array($check_table_result) or die("Error: an error has occurred while executing the query.");
    
                $query2 = "INSERT INTO holidays (employee_profile_id, request_date, start_date, end_date, holiday_type_id, reason, fileLocation, manager_approval, boss_approval, is_active) VALUES ('$employee_profile_id', '$request_date', '$start_date', '$end_date', '$holiday_type_id', '$reason', '$fileLoc', '$manager_approval', '$boss_approval', '$is_active')";
                $result2 = $link->query($query2) or die("Error: an error has occurred while executing the query.");
    
                if($result2)
                {
                    echo "\n\n works \n";
                }
            }
        }
        else
        {
            for ($i = 0; $i < count($array); $i++)
            {
                if ($array[$i + 1] - $array[$i] > 1)
                {
                    $test[] = [$r, $array[$i]];
                    $r++;
                }
                else
                {
                    $test[] = [$r, $array[$i]];
                }
            }

            print_r($test);
            echo "\n\n";
            foreach ($test as $array) 
            {
                if (count($array) >= 2) 
                {
                    $value1 = $array[0];
                    $value2 = $array[1];
                    $val3 = count($test) / 2;
                    $val4 =  count($test) - 1;
                    
                    for($i = 0; $i < $val3; $i++)
                    {
                        if($i == $value1)
                        {
                            if($t != $i)
                            {
                                echo "break here \n";
                                // database
                                $maxValue = max($temp);
                                $minValue = min($temp);
                                echo "\nmax: " . $maxValue;
                                echo "\nmin: " . $minValue . "\n";

                                $thsDay = "2024/03/$minValue";
                                $scdDay = "2024/03/$maxValue";
                                $todayRequest = date("Y/m/d");
                                $type = "Vacation";

                                $date_now = new DateTime();
                                $date2    = new DateTime("03/$minValue/2024");
                                // check if date is in the past
                                if ($date_now > $date2) 
                                {
                                    echo 'the date that you are asking is in the past.';
                                }
                                else
                                {
                                    $employee_profile_id = mysqli_real_escape_string($link, 1);
                                    $request_date = mysqli_real_escape_string($link, $todayRequest);
                                    $start_date = mysqli_real_escape_string($link, $thsDay);
                                    $end_date = mysqli_real_escape_string($link, $scdDay);
                                    $holiday_type_id = mysqli_real_escape_string($link, 1);
                                    $reason = mysqli_real_escape_string($link, "Vacation");
                                    $fileLoc = mysqli_real_escape_string($link, "");
                                    $manager_approval = mysqli_real_escape_string($link, 0);
                                    $boss_approval = mysqli_real_escape_string($link, 0);
                                    $is_active = mysqli_real_escape_string($link, 1);

                                    $check_table = "SELECT * FROM holidays WHERE employee_profile_id = $employee_profile_id";
                                    $check_table_result = $link->query($check_table) or die("Error: an error has occurred while executing the query.");
                                    $check = mysqli_fetch_array($check_table_result) or die("Error: an error has occurred while executing the query.");

                                    $query2 = "INSERT INTO holidays (employee_profile_id, request_date, start_date, end_date, holiday_type_id, reason, fileLocation, manager_approval, boss_approval, is_active) VALUES ('$employee_profile_id', '$request_date', '$start_date', '$end_date', '$holiday_type_id', '$reason', '$fileLoc', '$manager_approval', '$boss_approval', '$is_active')";
                                    $result2 = $link->query($query2) or die("Error: an error has occurred while executing the query.");

                                    if($result2)
                                    {
                                        echo "\n\n works \n";
                                    }
                                }
                                
                                $temp = array();
                                $t++;
                            }
                    
                            echo "In this set $value1 is the value $value2 \n";
                            array_push($temp, $value2);
                            if($j == $val4)
                            {
                                echo "end\n";
                                $maxValue = max($temp);
                                $minValue = min($temp);
                                echo "\nmax: " . $maxValue;
                                echo "\nmin: " . $minValue . "\n";

                                $thsDay = "2024/03/$minValue";
                                $scdDay = "2024/03/$maxValue";
                                $todayRequest = date("Y/m/d");
                                $type = "Vacation";

                                $date_now = new DateTime();
                                $date2    = new DateTime("03/$minValue/2024");
                                // check if date is in the past
                                if ($date_now > $date2) 
                                {
                                    echo 'the date that you are asking is in the past.';
                                }
                                else
                                {
                                    $employee_profile_id = mysqli_real_escape_string($link, 1);
                                    $request_date = mysqli_real_escape_string($link, $todayRequest);
                                    $start_date = mysqli_real_escape_string($link, $thsDay);
                                    $end_date = mysqli_real_escape_string($link, $scdDay);
                                    $holiday_type_id = mysqli_real_escape_string($link, 1);
                                    $reason = mysqli_real_escape_string($link, "Vacation");
                                    $fileLoc = mysqli_real_escape_string($link, "");
                                    $manager_approval = mysqli_real_escape_string($link, 0);
                                    $boss_approval = mysqli_real_escape_string($link, 0);
                                    $is_active = mysqli_real_escape_string($link, 1);

                                    $check_table = "SELECT * FROM holidays WHERE employee_profile_id = $employee_profile_id";
                                    $check_table_result = $link->query($check_table) or die("Error: an error has occurred while executing the query.");
                                    $check = mysqli_fetch_array($check_table_result) or die("Error: an error has occurred while executing the query.");

                                    $query2 = "INSERT INTO holidays (employee_profile_id, request_date, start_date, end_date, holiday_type_id, reason, fileLocation, manager_approval, boss_approval, is_active) VALUES ('$employee_profile_id', '$request_date', '$start_date', '$end_date', '$holiday_type_id', '$reason', '$fileLoc', '$manager_approval', '$boss_approval', '$is_active')";
                                    $result2 = $link->query($query2) or die("Error: an error has occurred while executing the query.");

                                    if($result2)
                                    {
                                        echo "\n\n works \n";
                                    }
                                }
                            }
                            $j++;

                        }
                    }
                    //echo "First value: $value1, Second value: $value2\n $val3";
                }
            }

        }
    
        
        
        
        
        // $idNum = $_POST['dayNum'];
        // $_SESSION['numCal'][] = $idNum;
        
        // testing max and min
        

        //calandar day
        //$day = strval($_SESSION['numCal']);
        
        //holiday_types
        // echo 'yes green I guess';

        
        
        // $query = "INSERT INTO holiday_types ('type') VALUES ('$type')";
        // $query = "SELECT * FROM holiday_types";
        
        // $result = $link->query($query) or die("Error: an error has occurred while executing the query.");
        // if ($result) 
        // {
        //     echo("\nAdd type successful!\n");
        //     while ($row = mysqli_fetch_array($result))
        //     {
                
        //         $typeDB = $row['type'];
        //         echo $typeDB;
        //     }
        //     echo $_SESSION['numCal'];
        // }

        



        


        
    }
    else
    {
        echo 'nothing selected';
    }
    /*
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
    */
} 
else if(isset($_POST['cancel']))
{
    // Unset session variable
    unset($_SESSION['user']);
    unset($_SESSION['currentM']);
    unset($_SESSION['currentY']);

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