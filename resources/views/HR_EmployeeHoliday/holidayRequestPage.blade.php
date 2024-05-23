<?php
    session_start();

    if (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0') 
    {
        // if(isset($_GET['Mf']))
        // {
        //     unset($_GET['Mf']);
        // }
        
        // if(isset($_GET['Mb']))
        // {
        //     unset($_GET['Mb']);
        // }
        
        // if(isset($_SESSION['currentM']))
        // {
        //     unset($_SESSION['currentM']);
        // }

        // if(isset($_SESSION['currentY']))
        // {
        //     unset($_SESSION['currentY']);
        // }
        
        // session_destroy();
            // Unset session variable
        unset($_SESSION['user']);
        unset($_SESSION['currentM']);
        unset($_SESSION['currentY']);

        // Destroy the session
        session_destroy();
    }

    $user_id = auth()->user()->employee_profile_id;
    //$user_userName = auth()->
    //echo $user_id;

    $host = '127.0.0.1';
    $user = 'root';
    $password = '';
    $database = 'energy_supplier';

    $credit2 = 0;

    $link = mysqli_connect($host, $user, $password, $database) or die("Error: no connection can be made to the host");
    mysqli_select_db($link, $database) or die("Error: the database could not be opened");

    // check balance of the user.
    // $queryGetEmpId = "SELECT `employee_profile_id` FROM `users` WHERE `` "
    $query = "SELECT * FROM `balances` WHERE `employee_profile_id` = $user_id AND `holiday_type_id` = 1";
    $result = $link->query($query) or die("Error: an error has occurred while executing the query.");
   
    while ($row = mysqli_fetch_array($result))
    {
        if(!isset($_SESSION['credit']))
        {
            $numOne = $row['yearly_holiday_credit'];
            $numTwo = $row['used_holiday_credit'];
            $_SESSION['credit'] = $numOne - $numTwo;
        }
            
        
        $credit = $_SESSION['credit'];
    }

    $queryy = "SELECT * FROM `balances` WHERE `employee_profile_id` = $user_id AND `holiday_type_id` = 2";
    $resultt = $link->query($queryy) or die("Error: an error has occurred while executing the query.");
   
    while ($row = mysqli_fetch_array($resultt))
    {
        if(!isset($_SESSION['credit2']))
        {
            $numOne1 = $row['yearly_holiday_credit'];
            $numTwo2 = $row['used_holiday_credit'];
            $_SESSION['credit2'] = $numOne1 - $numTwo2;
        }
        
        $credit2 = $_SESSION['credit2'];
    }

    $queryU = "SELECT * FROM `users`";
    $resultU = $link->query($queryU) or die("Error: an error has occurred while executing the query.");

    while ($row = mysqli_fetch_array($resultU))
    {
        $fName = $row['first_name'];
        $lName = $row['last_name'];
        $empID = $row['id'];

        $usersCompany[] = "$fName  $lName";
        $empIdList[] = "$empID";
        
    }

    if(!(isset($_SESSION['currentM'])) && !(isset($_GET['Mf'])))
    {
        $_SESSION['currentM'] = date('n');
        $t = $_SESSION['currentM'];
    }
    else if (isset($_GET['Mf']))
    {
        if(!(isset($_SESSION['currentM'])))
            $_SESSION['currentM'] = date('n');
        $t = $_SESSION['currentM'] + 1;
    }
    else if (isset($_GET['Mb']))
    {
        $t = $_SESSION['currentM'] - 1;
    }
    else if(isset($_GET['cncel']))
    {
        $t = date('n');
    }
    else
    {
        $t = 0;
    }

    
    // echo $t;

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
                // for ($i = $reqDays[0]; $i <= $reqDays[1]; $i++) 
                // {
                //     $reqDays[] = $i;
                // }
                for($j = $dayReq; $j < $e_dayReq; $j++)
                {
                    $reqDays[] = $j;
                }
                //print_r($reqDays);
            } 
        }
        //print_r($reqDays);
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
                // for ($i = $reqDays[0]; $i <= $reqDays[1]; $i++) 
                // {
                //     $reqDays[] = $i;
                // }
                for($j = $dayReq; $j < $e_dayReq; $j++)
                {
                    $reqAcptDays[] = $j;
                }
                // print_r($reqDays);
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
    <x-app-layout :title="'Calendar'">    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
               

    <h1></h1>
    <!-- <h2>
        welcome user: {{ $user_name}} id: {{$user_id}}
    </h2> -->

    <div class="table-container">
        <?php
            $wee = array();

            $currentMonth1 = date('n');
            $currentYear1 = date('Y');
            if(isset($_GET['Mf']))
            {
                unset($_SESSION['numCal']);

                // echo $_SESSION['currentM'];
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
                // echo $_SESSION['currentM'];

                //$t = $t == 12 ? $t = 0 : $t = $_SESSION['currentM'];
                $t = $_SESSION['currentM'] + 1;
                if($t == 13)
                    $t = 1;
                
            }
            else if(isset($_GET['Mb']))
            {
                unset($_SESSION['numCal']);

                // echo $_SESSION['currentM'];
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

                // echo $_SESSION['currentM'];
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
                // echo $currentYear;
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
            
            
            
            echo "<h1 id='monthName'> $monthsName[$numMonth] </h1>";
            
            if(isset($_GET['cncel']))
            {
                // echo "pressed";
                
                unset($_SESSION['currentM']);
                unset($_SESSION['currentY']);
                session_destroy();
            }
            // Start the table and iterate through each day of the month
            echo "<div id='cal_btns'>";
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
                        array_push($wee, $dayCount);
                        //$wee = ["$dayCount"];
                        $dayCount++;
                    } 
                    else 
                    {
                        $class = isset($_SESSION['addedCells'][$dayCount]) ? 'added' : '';
                        echo "<td class='{$class}' id='td_cal'>{$dayCount}</td>";
                        $dayCount++;
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "<table id='selectionTable'>
            <tr>
                <td id='selection'>
                    <p>Vacation</p>
                    <button id='btn' onclick='addDate(`green`)'><div id='update-me' class='square'>$credit</div></button>
                </td>
            </tr>
            <tr>
                <td id='selection'>
                    <p>Parental Leave</p>
                    <button id='btn' onclick='addDate(`purple`)'><div id='update-me2' class='square2'>$credit2</div>
                </td>
            </tr>
            <tr>
                <td id='selection'>
                    <p>Sick Leave</p>
                    <button id='btn' onclick='addDate(`pink`)'><div class='square3'></div>
                </td>
            </tr>
        </table>";
            echo "</div>";
            $myArrayJSON = json_encode($wee);
        ?>
        
        <?php echo "<div><form method='get' id='form_left'><button class='btn1' id='floBtn' name='cncel' value=''>home</button><button class='btn1' id='floBtn' name='Mf' value='$t'>next</button><button class='btn1' id='floBtnB' name='Mb' value='$t' disabled>back</button></form></div>";?> 
        <br>  
        <div >
            <p id="errorMsg">The date that you are asking is in the past.</p>
            <p id="scsMsg">The request has been send.</p>
            <!-- <p id="errorCredit">Sorry you don't have enough credit.You only have <?php /*echo $credit; */?> more days.</p> -->
            <p id="errorCredit">Sorry you don't have enough credit.</p>
        </div>
        <br>
        <div class="sidebar">
            <label id="label">This label is currently empty</label>
            <br>
            <button class='btn1' id="cancel" name="cancel" onclick="cnlButton()">Cancel</button>
            <button class='btn1' id="button" name="button" onclick="btnClicked()">Submit</button>
        </div>
    </div>
    <div id="div-right">
       
        <input type="file" id="myFile" name="filename">
        <button type="submit" onclick="sendFile()" id="myFile1">Upload File</button>
        <br>
        
        <label for="reason" id='reason1'>Reason:</label>
        <br>
        <textarea id="reason" name="reason" rows="4" cols="50"></textarea>
        <br>
        <select name="cars" id="people">
            <?php
                for($i = 0; $i < count($usersCompany); $i++)
                {
                    echo "<option value='$empIdList[$i]'>$usersCompany[$i]</option>";
                }
            ?>
        </select>
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

        let isCtrlPressed = false;
        var dayNumList = [];
        var dayWithoutWE = [];
        var num_WE = 0;

        var clr1 = "";

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

        function sendFile()
        {
            var fileInput = document.getElementById('myFile');
            var file = fileInput.files[0];

            var xhr = new XMLHttpRequest();
            var formData = new FormData();
            formData.append('filename', file);

            var url =  "{{ route('upload.file') }}";

            xhr.open('POST', url, true);
            xhr.onload = function() {
                if (xhr.readyState == 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response.path);
                }
            };

            xhr.send(formData);
        }

        function sendingDate(i, selected, len_selectedElements, clr_var)
        {
            //$idNum = document.getElementById("label").textContent;
            <?php /*if(isset($_POST['idNum'])){$idNum = $_POST['idNum'];}*/?>
            // if(typeof $idNum !== 'undefined')
            // {
                var dayNum = dayNumList[i]; 
                var date_now = new Date();
                var clr_str = '';
                
                var dateMonth = <?php if(isset($_SESSION['currentM'])){echo $_SESSION['currentM'];}else{ echo 0;} ?>;
                var date2 = new Date("2024-" + dateMonth + "-" + dayNum);
                var div2 = document.getElementById('errorMsg');
                var divSick = document.getElementById('myFile');
                var divSick1 = document.getElementById('myFile1');
                var divSick2 = document.getElementById('people');
                var divSick3 = document.getElementById('reason1');
                var divSick4 = document.getElementById('reason');
                var emilyHR = <?php if($user_id == 4){echo 1;}else{echo 0;}?>;
                // check if date is in the past
                if (date_now > date2) 
                {
                    div2.style.visibility='visible'
                    console.log("<?php echo $credit; ?>")
                    dayNumList = [];
                }
                else
                {
                    div2.style.visibility='hidden'
                    if(clr_var == 'green')
                    {
                        selected.classList.add("added");
                    }
                    else if(clr_var == 'pink')
                    {
                        selected.classList.add("added3");
                        // This is already in the sickleaverequest page
                        divSick.style.visibility='visible';
                        divSick1.style.visibility='visible';
                        divSick3.style.visibility='visible';
                        divSick4.style.visibility='visible';
                        if(emilyHR)
                        {
                            divSick2.style.visibility='visible';
                        }
                           
                        clr1 = clr_var;
                    }
                    else if(clr_var == 'green')
                    {
                        selected.classList.add("added2");
                    }
                    
                    //$color = 'green';
                    clr_str = clr_var + '=';

                   
                    countColor(clr_str, dayWithoutWE.length)
                }
                

            //}
        }

        function checkingDays(i, selected, len_selectedElements, clr_var)
        {
            var testW = len_selectedElements;
            var dayNum = dayNumList[i]; 
            
            var date_now = new Date();
            
            var dateMonth = <?php if(isset($_SESSION['currentM'])){echo $_SESSION['currentM'];}else{ echo 0;} ?>;
            var date2 = new Date("2024-" + dateMonth + "-" + dayNum);
            var div2 = document.getElementById('errorMsg');

            if (date_now > date2) 
            {
                div2.style.visibility='visible'
                console.log("<?php echo $credit; ?>")
            }
            else if(clr_var == 'green')
            {
                div2.style.visibility='hidden'
                selected.classList.add("added");
            
                console.log("len: " + testW);
            }
            else if(clr_var == 'pink')
            {
                div2.style.visibility='hidden'
                selected.classList.add("added3");
            }
            else if(clr_var == 'purple')
            {
                div2.style.visibility='hidden'
                selected.classList.add("added2");
            }
            
        }



        function addDate(clr_var) 
        {
            //const selected = document.querySelector(".selected");
            const selectedElements = document.querySelectorAll(".selected");
            div4.style.visibility='hidden';
            var len_selectedElements = selectedElements.length;
            var div2 = document.getElementById('errorMsg');
            
            

            for (var i = 0; i < selectedElements.length; i++) 
            {
                const selected = selectedElements[i];
                if (selected) 
                {
                    if(clr_var == 'green')
                    {
                        var newId = <?php echo $credit; ?>;
                        var elem = document.getElementById('update-me');

                        if(selected.classList.contains('prev-month') || selected.classList.contains('req-day') || selected.classList.contains('req-Acpt-day'))
                        {

                        }
                        else if (selected.classList.contains('added'))
                        {
                            selected.classList.remove("added");
                        }
                        else
                        {
                            checkingDays(i, selected, len_selectedElements, clr_var);

                            if(i == selectedElements.length -1)
                            {
                                sendingDate(i, selected, len_selectedElements, clr_var);
                            }
                            newId -= selectedElements.length;
                            //console.log(userCredit);
                        }
                    }
                    else if(clr_var == 'pink')
                    {
                        if(selected.classList.contains('prev-month') || selected.classList.contains('req-day') || selected.classList.contains('req-Acpt-day'))
                        {

                        }
                        else if (selected.classList.contains('added3'))
                        {
                            selected.classList.remove("added3");
                        }
                        else
                        {
                            //TODO: check enough sickdays 
                            checkingDays(i, selected, len_selectedElements, clr_var);

                            if(i == selectedElements.length -1)
                            {
                                sendingDate(i, selected, len_selectedElements, clr_var);
                            }
                        }
                    }
                    else if(clr_var == 'purple')
                    {
                        var newId = <?php echo $credit2; ?>;
                        var elem = document.getElementById('update-me2');

                        if(selected.classList.contains('prev-month') || selected.classList.contains('req-day') || selected.classList.contains('req-Acpt-day'))
                        {

                        }
                        else if (selected.classList.contains('added2'))
                        {
                            selected.classList.remove("added2");
                        }
                        else
                        {
                            //TODO: check enough sickdays 
                            checkingDays(i, selected, len_selectedElements, clr_var);

                            if(i == selectedElements.length -1)
                            {
                                sendingDate(i, selected, len_selectedElements, clr_var);
                            }
                        }
                        newId -= selectedElements.length;
                        if(newId < 0)
                            div4.style.visibility='visible';
                    }
                    
                    if((clr_var == 'green' || clr_var == 'purple') && newId > 0 )
                    {

                    
                        elem.innerHTML = newId;
                        var xhr = new XMLHttpRequest();
                        if (xhr == null) 
                        {
                            alert("Browser does not support HTTP Request");
                        } 
                        else 
                        {
                            var url = "{{ asset('php/calendarRequest.php') }}";
                            if(clr_var == 'green')
                            {
                                var params = "creditGreen=" + encodeURIComponent(newId);
                            }
                            else if(clr_var == 'purple')
                            {
                                var params = "creditPurple=" + encodeURIComponent(newId);
                            }
                            
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
                    
                    selected.classList.remove("selected");
                    
                }
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
        // function addDate3() 
        // {
        //     const selected = document.querySelector(".selected");
    
        //     if (selected) 
        //     {
        //         if(selected.classList.contains('prev-month'))
        //         {

        //         }
        //         else if (selected.classList.contains('added3'))
        //         {
        //             selected.classList.remove("added3");
        //             // updateSession(selected.textContent, 'remove');
        //         }
        //         else
        //         {
        //             selected.classList.add("added3");
        //             <?php /*if(isset($_POST['idNum'])){$idNum = $_POST['idNum'];}*/?>
        //             if(typeof $idNum !== 'undefined')
        //             {
        //                 $coor = 'pink';
        //                 //console.log($color + " " + $idNum);
        //                 numPink++;
        //                 countColor('pink=', numPink)
        //             }
        //             // updateSession(selected.textContent, 'add');
        //         }
        //         selected.classList.remove("selected3");
        //     }
        // }
    
        // document.getElementById("calendar").addEventListener("click", function(e) 
        // {
        //     if(e.target && e.target.nodeName === "TD") 
        //     {
        //         // Remove previous selection and 'added' class from all cells
        //         document.querySelectorAll("td").forEach(cell => {
        //             cell.classList.remove("selected");
        //             // Optionally remove 'added' class if you want each click to only mark one cell as added
        //             // cell.classList.remove("added");
        //         });
    
        //         // Add 'selected' class to the clicked cell
        //         e.target.classList.add("selected");
                
        //         // Update the label with the cell's number
        //         document.getElementById("label").textContent = e.target.textContent;
        //         $idNum = e.target.textContent;
        //         getDay();
                
        //     }
        // });

        document.getElementById("calendar").addEventListener("click", function(e) {
            if (e.target && e.target.nodeName === "TD") {
                if (!isCtrlPressed) {
                    // Remove previous selection and 'added' class from all cells
                    document.querySelectorAll("td").forEach(cell => {
                        cell.classList.remove("selected");
                        // Optionally remove 'added' class if you want each click to only mark one cell as added
                        // cell.classList.remove("added");
                    });
                }

                if(e.target.classList.contains('selected'))
                {
                    e.target.classList.remove("selected");
                    // Get the text content of the clicked cell
                    let selectedText = e.target.textContent.trim();

                    // Find the index of the selected text in dayNumList
                    let ind = dayNumList.indexOf(selectedText);

                    // If the selected text is found in dayNumList, remove it
                    if (ind !== -1) {
                        dayNumList.splice(ind, 1);
                    }
                }
                else
                {
                    e.target.classList.add("selected");

                    // Update the label with the selected cells' numbers
                    let selectedCells = document.querySelectorAll(".selected");
                    let labelContent = "";
                    selectedCells.forEach(cell => {
                        labelContent += cell.textContent + " ";
                        //console.log("label: " + labelContent.trim() + "\n")
                        if (!dayNumList.includes(cell.textContent)) 
                        {
                            dayNumList.push(cell.textContent);
                            var arrWE = <?php echo $myArrayJSON;?>;
                            console.log("arrWE: " + arrWE);
                            if(!arrWE.includes(Number(cell.textContent)))
                            {
                                dayWithoutWE.push(cell.textContent);
                            }
                        }
                        
                    });
                    document.getElementById("label").textContent = labelContent.trim();
                    $idNum = labelContent.trim();
                    getDay();
                }
                console.log("dayNumList: "+ dayNumList + "\n" + "dayWithoutWE: " + dayWithoutWE);

            }
        });

        document.addEventListener("keydown", function(e) {
            if (e.key === "Control") {
                isCtrlPressed = true;
            }
        });

        document.addEventListener("keyup", function(e) {
            if (e.key === "Control") {
                isCtrlPressed = false;
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
            var cre2 = "<?php echo $credit2; ?>";
            var emilyHR = <?php if($user_id == 4){echo 1;}else{echo 0;}?>;
            var userId1 = <?php echo $user_id; ?>;
            const textarea = document.getElementById('reason');
            const textVal = textarea.value;
            const select = document.getElementById('people');
            const selectedOption = select.value;
            const myF = document.getElementById('myFile');
            const myFileName = myF.value;

            if(visBool)
            {
                cnlButton();
            }
            else if(numGr > cre)
            {
                
                div4.style.visibility='visible';
                console.log('bro, what are you doing??');
            }
            // else if(numGr > cre2)
            // {
            //     div4.style.visibility='visible';
            //     console.log('bro, what are you doing??');
            // }
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
                    var params = "button" + "&" + "userId1=" + encodeURIComponent(userId1); // Send $idNum value as a POST parameter
                    if(emilyHR)
                        var params = "button" + "&" + "reason=" + encodeURIComponent(textVal) + "&" + "person=" + encodeURIComponent(selectedOption) + "&" + "fileName=" +  encodeURIComponent(myFileName);
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
            
            window.location.href = "{{ route('request') }}";
        }

        window.addEventListener("load", function() {
        // if (window.sessionStorage.getItem("reloaded")) {
        //     // page was reloaded, do something
        // }
        window.sessionStorage.setItem("reloaded", true);
        });

    </script>


                                        </div>

            </div>
        </div>
    </div>

</x-app-layout>

</body>
</html>
