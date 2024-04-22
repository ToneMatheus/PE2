<?php
    session_start();

    if (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0') 
    {
        unset($_GET['Mf']);
        unset($_GET['Mb']);
        unset($_SESSION['currentM']);
        unset($_SESSION['currentY']);
        session_destroy();
    }

    $user_id = auth()->user()->employee_profile_id;
    //$user_userName = auth()->
    //echo $user_id;

    $host = '127.0.0.1';
    $user = 'root';
    $password = '';
    $database = 'energy_supplier';

    $link = mysqli_connect($host, $user, $password, $database) or die("Error: no connection can be made to the host");
    mysqli_select_db($link, $database) or die("Error: the database could not be opened");

    // check balance of the user.
    // $queryGetEmpId = "SELECT `employee_profile_id` FROM `users` WHERE `` "
    $query = "SELECT * FROM `balances` WHERE `employee_profile_id` = $user_id";
    $result = $link->query($query) or die("Error: an error has occurred while executing the query.");
   
    while ($row = mysqli_fetch_array($result))
    {
        $credit = $row['yearly_holiday_credit'];
        
    }

    if(!(isset($_SESSION['currentM'])) && !(isset($_GET['Mf'])))
    {
        $_SESSION['currentM'] = date('n');
        $t = $_SESSION['currentM'];
        echo "wut";
    }
    else if (isset($_GET['Mf']))
    {
        if(!(isset($_SESSION['currentM'])))
            $_SESSION['currentM'] = date('n');
        $t = $_SESSION['currentM'] + 1;
        echo "yes 2";
    }
    else if (isset($_GET['Mb']))
    {
        $t = $_SESSION['currentM'] - 1;
    }
    else if(isset($_GET['cncel']))
    {
        $t = date('n');
    }

    
    echo $t;

    $query2 = "SELECT * FROM `holidays` WHERE `employee_profile_id` = $user_id AND `manager_approval` != 1 AND MONTH(`start_date`) = $t AND MONTH(`end_date`) = $t";
    $result2 = $link->query($query2) or die("Error: an error has occurred while executing the query.");

    $query3 = "SELECT * FROM `holidays` WHERE `employee_profile_id` = $user_id AND `manager_approval` = 1 AND MONTH(`start_date`) = $t AND MONTH(`end_date`) = $t";
    $result3 = $link->query($query3) or die("Error: an error has occurred while executing the query.");

    $reqDays = [];
    $reqAcptDays = [];


    // SELECT * FROM `balances`

    

    if(!isset($_SESSION['currentY']))
    {
        $_SESSION['currentY'] = date('Y');
    }

    if(!isset($_SESSION['idUser']))
    {
        $_SESSION['idUser'] = $user_id;
    }
    else if($user_id !=  $_SESSION['idUser'])
    {
        $_SESSION['idUser'] = $user_id;
    }

    if(!isset($_SESSION['startM']))
    {
        $_SESSION['startM'] = date('n');
    }

    while ($row = mysqli_fetch_array($result2))
    {
        $strtDate = $row['start_date']; //end_date
        $ndDate = $row['end_date']; 
        
        $monthReq = date('m', strtotime($strtDate));
        $dayReq = date('d', strtotime($strtDate));

        $e_monthReq = date('m', strtotime($ndDate));
        $e_dayReq = date('d', strtotime($ndDate));

        if($t == $monthReq)
        {
            $reqDays[] = $dayReq;
        }

        if($t == $e_monthReq)
        {
            if (!(in_array($e_dayReq, $reqDays))) 
            {
                $reqDays[] = $e_dayReq;
            } 
        }
    }

    while ($row = mysqli_fetch_array($result3))
    {
        $strtDate = $row['start_date']; //end_date
        $ndDate = $row['end_date']; 
        
        $monthReq = date('m', strtotime($strtDate));
        $dayReq = date('d', strtotime($strtDate));

        $e_monthReq = date('m', strtotime($ndDate));
        $e_dayReq = date('d', strtotime($ndDate));

        if(date('n') == $monthReq)
        {
            $reqAcptDays[] = $dayReq;
        }

        if(date('n') == $e_monthReq)
        {
            if (!(in_array($e_dayReq, $reqDays))) 
            {
                $reqAcptDays[] = $e_dayReq;
            } 
        }
    }
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>calendar</title>
    
    <link href="{{ asset('css/calendar.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        
    </header>
    <h1>Calendar</h1>
    <h2>
        welcome user: {{ $user_name}} id: {{$user_id}}
    </h2>

    <div class="table-container">
        <?php
            $wee = array();

            $currentMonth1 = date('n');
            $currentYear1 = date('Y');
            if(isset($_GET['Mf']))
            {
                unset($_SESSION['numCal']);

                echo $_SESSION['currentM'];
                $t = $_SESSION['currentM'];

                $_SESSION['currentY'] = $currentYear1;
                $currentYear = $_SESSION['currentY']; 

                // Calculate the month and year for the next month
                if(!$_GET['Mf'] == "12" && $_SESSION['currentM'] == 0)
                {
                    $currentMonth = $_SESSION['currentM'] + 1;
                    $_SESSION['currentM'] = $currentMonth;
                }
                else if($_GET['Mf'] == "12" && $_SESSION['currentM'] == 0)
                {
                    $currentMonth = $currentMonth1 == 12 ? 1 : $currentMonth1 + 1;
                    $_SESSION['currentM'] = $currentMonth;
                }
                else
                {
                    $currentMonth = $_SESSION['currentM'] + 1;
                    $_SESSION['currentM'] = $currentMonth;
                }

                
               
             
                
                //$currentYear = $ted == 12 ? $currentYear1 + 1 : $currentYear1;
                echo $_SESSION['currentM'];

                //$t = $t == 12 ? $t = 0 : $t = $_SESSION['currentM'];
                $t = $_SESSION['currentM'] + 1;
                if($t == 13)
                    $t = 1;
                
            }
            else if(isset($_GET['Mb']))
            {
                unset($_SESSION['numCal']);

                echo $_SESSION['currentM'];
                if(!$_GET['Mb'] == "12" && $_SESSION['currentM'] == 0)
                {
                    $currentMonth = $_SESSION['currentM'] - 1;
                    $_SESSION['currentM'] = $currentMonth;
                }
                else if ($_GET['Mb'] == "12" && $_SESSION['currentM'] == 0)
                {
                    $currentMonth = $currentMonth1 == 12 ? 1 : $currentMonth1 - 1;
                    $_SESSION['currentM'] = $currentMonth;
                }
                else
                {
                    $currentMonth = $_SESSION['currentM'] - 1;
                    $_SESSION['currentM'] = $currentMonth;
                }
                
                $_SESSION['currentY'] = $currentYear1;
                $currentYear = $_SESSION['currentY']; 

                echo $_SESSION['currentM'];
                $t = $_SESSION['currentM'] - 1;
                if($t == 0)
                    $t = 12;

                
            }
            else
            {
                // Get the current month and year
                $currentMonth = date('n');
                $currentYear = date('Y');
            }

            // Calculate the month and year for the next month
            // $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
            // $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;

            if($currentMonth == 13)
            {
                $currentMonth = 1;
                $_SESSION['currentM'] = 1;
                $t = $_SESSION['currentM'];
                $_SESSION['currentM'] = $currentYear;
                if($currentYear != $currentYear1 - 1)
                    $currentYear++;
                $daysInPrevMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear - 1);
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            }
            else if($currentMonth == 0)
            {
                $currentMonth = 12;
                $_SESSION['currentM'] = 12;
                $t = 0;
                $_SESSION['currentM'] = $currentYear;
                if($currentYear == $currentYear1)
                    $currentYear--;
                echo $currentYear;
                $daysInPrevMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear - 1);
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            }
            else
            {
                $daysInPrevMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear - 1);
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            }

            // Get the number of days in the current month and the previous month
            

            // Calculate the day of the week of the first day of the month
            $firstDayOfMonth = date('N', strtotime("$currentYear-$currentMonth-01"));

            $monthsName = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
            $numMonth = $currentMonth - 1;
            
            
            echo "<form method='get'><button id='floBtn' name='cncel' value=''>home</button><button id='floBtn' name='Mf' value='$t'>next</button><button id='floBtnB' name='Mb' value='$t' disabled>back</button></form>";
            echo "<h1 id='monthName'> $monthsName[$numMonth] </h1>";
            
            if(isset($_GET['cncel']))
            {
                // echo "pressed";
                
                unset($_SESSION['currentM']);
                unset($_SESSION['currentY']);
                session_destroy();
            }
            // Start the table and iterate through each day of the month
            echo "<table id='calendar'>";
            $dayCount = 1;
            $prevMonthDayCount = $daysInPrevMonth - $firstDayOfMonth + 2;
            //echo "<tr><td> $currentMonth </td></tr>";
            for ($row = 0; $row < 6; $row++) 
            {
                echo "<tr>";
                for ($col = 1; $col <= 7; $col++) 
                {
                    if (in_array($dayCount, $reqAcptDays))
                    {
                        echo "<td class='req-Acpt-day'>$dayCount</td>";
                        $dayCount++;
                    }
                    elseif (in_array($dayCount, $reqDays))
                    {
                        echo "<td class='req-day'>$dayCount</td>";
                        $dayCount++;
                    }
                    else if ($prevMonthDayCount <= $daysInPrevMonth) 
                    {
                        // Fill in days from the previous month
                        echo "<td class='prev-month'>$prevMonthDayCount</td>";
                        $prevMonthDayCount++;
                    } 
                    else if ($dayCount > $daysInMonth) 
                    {
                        // Fill empty cells with days from the next month if needed
                        echo "<td class='prev-month'>" . ($dayCount - $daysInMonth) . "</td>";
                        $dayCount++;
                    }
                    else if($col == 6 || $col == 7)
                    {
                        $class = isset($_SESSION['addedCells'][$dayCount]) ? 'added' : '';
                        echo "<td class='{$class} weekend'>{$dayCount}</td>";
                        array_push($wee, "$dayCount");
                        //$wee = ["$dayCount"];
                        $dayCount++;
                    } 
                    else 
                    {
                        $class = isset($_SESSION['addedCells'][$dayCount]) ? 'added' : '';
                        echo "<td class='{$class}'>{$dayCount}</td>";
                        $dayCount++;
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        ?>
        

        <table>
            <tr>
                <td id='selection'>
                    <p>Vacation</p>
                    <button id="btn" onclick="addDate()"><div class="square"></div>
                </td>
            </tr>
            <tr>
                <td id='selection'>
                    <p>Parental Leave</p>
                    <button id="btn" onclick="addDate2()"><div class="square2"></div>
                </td>
            </tr>
            <tr>
                <td id='selection'>
                    <p>Sick Leave</p>
                    <button id="btn" onclick="addDate3()"><div class="square3"></div>
                </td>
            </tr>
        </table>   
        <div >
            <p id="errorMsg">The date that you are asking is in the past.</p>
            <p id="scsMsg">The request has been send.</p>
            <p id="errorCredit">Sorry you don't have enough credit. You only have <?php echo $credit; ?> more days.</p>
        </div>
        <br>
        <div class="sidebar">
            <label id="label">This label is currently empty</label>
            <button id="cancel" name="cancel" onclick="cnlButton()">Cancel</button>
            <button id="button" name="button" onclick="btnClicked()">Submit</button>
        </div>

        <form action="{{route('dashboard')}}">
            <button>dashboard</button>
        </form>   
    </div>
 
    <script>
        var visBool = false;
        let numGr = 0;
        let numPur = 0;
        let numPink = 0;
        var div4 = document.getElementById('errorCredit');
        var btn1 = document.getElementById('floBtnB');
        var arrWe = [];
        var toDay = new Date();

        var M = <?php 
        if(isset($_SESSION['currentM']))
            echo "$currentMonth";
        else
            echo 0;
        ?>;

        var Y = <?php 
        if(isset($_SESSION['currentY']))
            echo "$currentYear";
        else
            echo 0;
        ?>;

        var userID = <?php 
        if(isset($_SESSION['idUser']))
            echo "$user_id";
        else
            echo 0;
        ?>;
        //M--;
        console.log("M: " + M + "\n" + "Y: " + Y + "\n" + "userID: " + userID);
        var noDay = new Date(Y+"-"+M+"-"+"1")
        console.log("cal: " +noDay);

        if(noDay > toDay)
        //if(toDay < 3)
        {
            btn1.disabled = false;
        }



        function addDate() 
        {
            const selected = document.querySelector(".selected");
            div4.style.visibility='hidden';
    
            if (selected) 
            {
                if(selected.classList.contains('prev-month') || selected.classList.contains('req-day') || selected.classList.contains('req-Acpt-day'))
                {

                }
                else if (selected.classList.contains('added'))
                {
                    selected.classList.remove("added");
                    numGr--;
                    // updateSession(selected.textContent, 'remove');
                }
                else
                {
                    
                    //$idNum = document.getElementById("label").textContent;
                   <?php if(isset($_POST['idNum'])){$idNum = $_POST['idNum'];}?>
                    if(typeof $idNum !== 'undefined')
                    {
                       var dayNum = $idNum;
                        var date_now = new Date();
                        var idnum = <?php if(isset($_POST['idNum'])){echo $_POST['idNum'];}else{ echo 0;} ?>;
                        var dateMonth = <?php if(isset($_SESSION['currentM'])){echo $_SESSION['currentM'];}else{ echo 0;} ?>;
                        var date2    = new Date("2024-" + dateMonth + "-" + dayNum);
                        var div2 = document.getElementById('errorMsg');
                        // check if date is in the past
                        if (date_now > date2) 
                        {
                            div2.style.visibility='visible'
                            console.log("<?php echo $credit; ?>")
                        }
                        else
                        {
                            div2.style.visibility='hidden'
                            selected.classList.add("added");
                            $color = 'green';
                            //console.log($color  + " " +$idNum);
                            // console.log(<?php
                            //     $js_array = json_encode($wee);
                            //     echo "$js_array";
                            // ?>);
                            arrWe = (<?php
                                $js_array = json_encode($wee);
                                echo "$js_array";
                            ?>);

                            console.log(arrWe);
                            console.log(dayNum);

                            if(!arrWe.includes(dayNum))
                            {
                                numGr++;
                            }
                            console.log(numGr);
                            countColor('green=', numGr)
                        }
                       

                    }
                    
                    //getDay();
                    // updateSession(selected.textContent, 'add');
                }
                selected.classList.remove("selected");
            }
        }
        function addDate2() 
        {
            const selected = document.querySelector(".selected");
    
            if (selected) 
            {
                if(selected.classList.contains('prev-month'))
                {

                }
                else if (selected.classList.contains('added2'))
                {
                    selected.classList.remove("added2");
                    // updateSession(selected.textContent, 'remove');
                }
                else
                {
                    selected.classList.add("added2");
                    <?php if(isset($_POST['idNum'])){$idNum = $_POST['idNum'];}?>
                    if(typeof $idNum !== 'undefined')
                    {
                        $color = 'purple';
                        //console.log($color  + " " + $idNum);
                        numPur++;
                        countColor('purple=', numPur)

                    }
                    // updateSession(selected.textContent, 'add');
                }
                selected.classList.remove("selected");
            }
        }
        function addDate3() 
        {
            const selected = document.querySelector(".selected");
    
            if (selected) 
            {
                if(selected.classList.contains('prev-month'))
                {

                }
                else if (selected.classList.contains('added3'))
                {
                    selected.classList.remove("added3");
                    // updateSession(selected.textContent, 'remove');
                }
                else
                {
                    selected.classList.add("added3");
                    <?php if(isset($_POST['idNum'])){$idNum = $_POST['idNum'];}?>
                    if(typeof $idNum !== 'undefined')
                    {
                        $coor = 'pink';
                        //console.log($color + " " + $idNum);
                        numPink++;
                        countColor('pink=', numPink)
                    }
                    // updateSession(selected.textContent, 'add');
                }
                selected.classList.remove("selected3");
            }
        }
    
        document.getElementById("calendar").addEventListener("click", function(e) 
        {
            if(e.target && e.target.nodeName === "TD") 
            {
                // Remove previous selection and 'added' class from all cells
                document.querySelectorAll("td").forEach(cell => {
                    cell.classList.remove("selected");
                    // Optionally remove 'added' class if you want each click to only mark one cell as added
                    // cell.classList.remove("added");
                });
    
                // Add 'selected' class to the clicked cell
                e.target.classList.add("selected");
                
                // Update the label with the cell's number
                document.getElementById("label").textContent = e.target.textContent;
                $idNum = e.target.textContent;
                getDay();
                
            }
        });

        function getDay() 
        {
            var xhr = new XMLHttpRequest();
            if (xhr == null) 
            {
                alert("Browser does not support HTTP Request");
            } 
            else 
            {
                var url = "{{ asset('php/calendarRequest.php') }}";
                var params = "idNum=" + $idNum; // Send $idNum value as a POST parameter
                //params += "color=" + $color;
                xhr.open("POST", url, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) 
                    {
                        // Handle the response from the server if needed
                        console.log(xhr.responseText);
                    }
                };
                xhr.send(params);
            }
        }

        function countColor($color, $num)
        {
            if($num != 0)
            {
                var xhr = new XMLHttpRequest();
                if (xhr == null) 
                {
                    alert("Browser does not support HTTP Request");
                } 
                else 
                {
                    var url = "{{ asset('php/calendarRequest.php') }}";
                    var idNum = $idNum;
                    //var color = $color;
                    var color = $num;
                    var params = "dayNum=" + encodeURIComponent(idNum) + "&" + $color + encodeURIComponent(color);
                    //params += "color=" + $color;
                    xhr.open("POST", url, true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) 
                        {
                            // Handle the response from the server if needed
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.send(params);
                }
            }
        }


        function btnClicked()
        {
            var cre = "<?php echo $credit; ?>";
            if(visBool)
            {
                cnlButton();
            }
            else if(numGr > cre)
            {
                
                div4.style.visibility='visible';
                console.log('bro, what are you doing??');
            }
            else
            {
                var div3 = document.getElementById('scsMsg');
                div3.style.visibility='visible';
                visBool = true;

                var xhr = new XMLHttpRequest();
                if (xhr == null) 
                {
                    alert("Browser does not support HTTP Request");
                } 
                else 
                {
                    var url = "{{ asset('php/calendarRequest.php') }}";
                    var params = "button"; // Send $idNum value as a POST parameter
                    //params += "color=" + $color;
                    xhr.open("POST", url, true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) 
                        {
                            // Handle the response from the server if needed
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.send(params);
                }
            }
        }

        function cnlButton()
        {
            var xhr = new XMLHttpRequest();
            if (xhr == null) 
            {
                alert("Browser does not support HTTP Request");
            } 
            else 
            {
                var url = "{{ asset('php/calendarRequest.php') }}";
                var params = "cancel"; // Send $idNum value as a POST parameter
                //params += "color=" + $color;
                xhr.open("POST", url, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) 
                    {
                        // Handle the response from the server if needed
                        console.log(xhr.responseText);
                    }
                };
                xhr.send(params);
            }

            window.location.href = "{{route('request')}}";
        }

    </script>



</body>
</html>
