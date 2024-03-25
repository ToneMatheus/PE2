<?php
    session_start();
// function debug_to_console($data) 
    // {
    //     $output = $data;
    //     if (is_array($output))
    //         $output = implode(',', $output);
    
    //     echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    // }

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

    <div class="table-container">
        <?php
            // Get the current month and year
            $currentMonth = date('n');
            $currentYear = date('Y');

            // Get the number of days in the current month and the previous month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            $daysInPrevMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth - 1, $currentYear);

            // Calculate the month and year for the next month
            $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
            $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;

            // Calculate the day of the week of the first day of the month
            $firstDayOfMonth = date('N', strtotime("$currentYear-$currentMonth-01"));

            $monthsName = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
            $numMonth = $currentMonth - 1;
            echo "<button id='floBtn'>next</button><button id='floBtn'>back</button>";
            echo "<h1 id='monthName'> $monthsName[$numMonth] </h1>";

            // Start the table and iterate through each day of the month
            echo "<table id='calendar'>";
            $dayCount = 1;
            $prevMonthDayCount = $daysInPrevMonth - $firstDayOfMonth + 2;
            //echo "<tr><td> $currentMonth </td></tr>";
            for ($row = 0; $row < 5; $row++) 
            {
                echo "<tr>";
                for ($col = 1; $col <= 7; $col++) 
                {
                    if ($prevMonthDayCount <= $daysInPrevMonth) 
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
        <div id="errorMsg">
            <p>The date that you are asking is in the past.</p>
        </div>
        <div id="scsMsg">
            <p>The request has been send.</p>
        </div>
        <div class="sidebar">
            <label id="label">This label is currently empty</label>
            <button id="cancel" name="cancel" onclick="cnlButton()">Cancel</button>
            <button id="button" name="button" onclick="btnClicked()">Submit</button>
        </div>
        
    </div>

    <script>
        var visBool = false;
        let numGr = 0;
        let numPur = 0;
        let numPink = 0;

        function addDate() 
        {
            const selected = document.querySelector(".selected");
    
            if (selected) 
            {
                if(selected.classList.contains('prev-month'))
                {

                }
                else if (selected.classList.contains('added'))
                {
                    selected.classList.remove("added");
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
                        var date2    = new Date("2024-03-" + dayNum);
                        var div2 = document.getElementById('errorMsg');
                        // check if date is in the past
                        if (date_now > date2) 
                        {
                            div2.style.visibility='visible'
                        }
                        else
                        {
                            div2.style.visibility='hidden'
                            selected.classList.add("added");
                            $color = 'green';
                            //console.log($color  + " " +$idNum);
                            numGr++;
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
                var url = "{{ asset('php/test.php') }}";
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
            var xhr = new XMLHttpRequest();
            if (xhr == null) 
            {
                alert("Browser does not support HTTP Request");
            } 
            else 
            {
                var url = "{{ asset('php/test.php') }}";
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

        function btnClicked()
        {
            if(visBool)
            {
                cnlButton();
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
                    var url = "{{ asset('php/test.php') }}";
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
                var url = "{{ asset('php/test.php') }}";
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
