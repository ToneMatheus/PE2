<?php
session_start();
if (!isset($_SESSION['user'])) 
{
    $_SESSION['user'] = array();
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idNum'])) 
{
    $idNum = $_POST['idNum'];
    
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
    echo $_SESSION['user']['green'];
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
else 
{
    echo "No data received.";
}

// Unset session variable
unset($_SESSION['user']);

// Destroy the session
session_destroy();
?>