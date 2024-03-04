<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "energy_supplier";

    $conn = mysqli_connect($host, $user, $password, $db);

    if (!$conn) {
        die("Connection to database failed.");
        }
    if(isset($_POST['submitTariff']) || isset($_POST['submitChangeTariff'])) {
            $name = $_POST['name'];
            $rangeMin = $_POST['rangeMin'];
            $rate = $_POST['rate'];
            
            if(!empty($_POST['rangeMax'])){
                $rangeMax = $_POST['rangeMax'];
            } else {
                $rangeMax = null;
            }

            if (isset($_POST['type'])){
                $type = $_POST['type'];
            }

            if (isset($_GET['id'])){
                $id = $_GET['id'];
            }

            if(isset($_POST['submitTariff'])){
                $stmt = $conn->prepare('INSERT INTO tariff (`name`, `type`, rangeMin, rangeMax, rate)
                VALUES (?, ?, ?, ?, ?)');

                $stmt->bind_param('ssddd', $name, $type, $rangeMin, $rangeMax, $rate);
            } else {
                $stmt = $conn->prepare('UPDATE tariff
                                        SET `name` = ?, rangeMin = ?, rangeMax = ?, rate = ?
                                        WHERE ID = ?');

                $stmt->bind_param('sdddi', $name, $rangeMin, $rangeMax, $rate, $id);
            }

            $stmt->execute();
            $stmt->close();

            header('Location: ./tariff.php');
    }

    if (isset($_GET['id']) && $_GET['action'] == 'delete'){
        $id = $_GET['id'];

        $stmt = $conn->prepare('DELETE FROM tariff WHERE ID LIKE ?');
        $stmt->bind_param('i', $id);

        $stmt->execute();
        $stmt->close();

        header('Location: ./tariff.php');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tariffs</title>
        <meta charset="utf-8"/>
        <link href="/css/tariff.css" rel="stylesheet"/>
        <script>        //Nog safety checks
            function showForm() {
                document.getElementById('addTariff').style.display = 'block';
            }

            function confirmCancel() {
                document.getElementById('confirmCancel').style.display = 'block';
            }

            function confirmYes(){
                location.href = "./tariff.php";
            }

            function confirmNo(){
                document.getElementById('confirmCancel').style.display = 'none';
            }

            function checkAddTariff(){
                var name = document.getElementById('name').value;
                var type = document.getElementById('type').value;
                var rangeMin = document.getElementById('rangeMin').value;
                var rangeMax = document.getElementById('rangeMax').value;
                var rate = document.getElementById('rate').value;

                var error = document.getElementById('error1');

                if(!name || !type || !rangeMin || !rate){
                    error.innerHTML = 'Fill in all fields';
                    return false;
                }

                /*if(rangeMax){
                    if (rangeMin > rangeMax){
                        error.innerHTML = 'Range Minimum should be smaller than Range Maximmum';
                        return false;
                    }
                }*/

                return true;
            }

            function sortTariffTable(n){
                var rows, switching, i, x, y, dir, switchCount = 0;
                var table = document.getElementById('tariffTable');
                
                dir = 'asc';
                switching = true;

                while(switching){
                    switching = false;
                    rows = table.rows;

                    for(i = 1; i < (rows.length - 1); i++){

                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i+1].getElementsByTagName("TD")[n];

                        var numX = parseFloat(x.innerHTML);
                        var numY = parseFloat(y.innerHTML);

                        if(!isNaN(numX) && !isNaN(numY)){
                            if(dir == "asc" && numX > numY ||
                               dir == "desc" && numX < numY){
                            
                            swapRows(x, y);
                            switching = true;
                            switchCount++;
                        }
                        } else {
                            if(dir == "asc" && x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase() ||
                               dir == "desc" && x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()){
                            
                            swapRows(x, y);
                            switching = true;
                            switchCount++;
                        }
                        }
                    }

                    if(!switching && switchCount == 0 && dir == "asc"){
                        dir = "desc";
                        switching = true;
                    }
                }
            }

            function swapRows(x, y){            
                var tempRow = x.innerHTML;

                x.innerHTML = y.innerHTML;
                y.innerHTML = tempRow;
            }
        </script>
    </head>
    <body>
        <h1>Tariffs</h1>

        <h2>Electrical</h2>
        <?php 
            $stmt = 'SELECT `type` FROM tariff';
            $result = $conn->query($stmt);

            if($result->num_rows > 0){
                $types = array();

                while($row = $result->fetch_assoc()){
                    if (!in_array($row['type'], $types)){
                        array_push($types, $row['type']);
                    }
                }

                $result->free();

                foreach($types as $type){
                    echo '<h3>' . $type . '</h3>';

                    $stmt = $conn->prepare('SELECT * FROM tariff WHERE `type` LIKE ?');
                    $stmt->bind_param('s', $type);
                    $stmt->execute();

                    $result = $stmt->get_result();

                    echo '<table id="tariffTable"><tr>
                        <th onclick="sortTariffTable(0)">Name</th>
                        <th onclick="sortTariffTable(1)">Range Min</th>
                        <th onclick="sortTariffTable(2)">Range Max</th>
                        <th onclick="sortTariffTable(3)">Rate</th>
                        <th>Edit</th>
                        <th>Delete</th></tr>
                    ';

                    while($row = $result->fetch_assoc()){

                        if(isset($_GET['id']) && $_GET['action'] == 'edit'){            //Edit row
                            if ($row['ID'] == $_GET['id']){                            //Show edit state for row
                                $options = array('Tier 1', 'Tier 2', 'Tier 3');
                                echo '<form id="changeTariff" method="post">
                                <tr><td>
                                    <select name="name" id="name">';

                                    foreach ($options as $option){
                                        if ($option == $row['name']){
                                            echo '<option value="' . $option . '" selected>' . $option . '</option>';
                                        } else {
                                            echo '<option value="' . $option . '">' . $option . '</option>';
                                        }
                                    }

                                echo '</select></td>
                                <td>
                                    <input type="number" name="rangeMin" id="rangeMin" min="0" value="' . $row['rangeMin'] .'"/> 
                                </td>
                                <td>
                                    <input type="number" name="rangeMax" id="rangeMax" min="0" value="' . $row['rangeMax'] .'"/> 
                                </td>
                                <td>
                                    <input type="number" name="rate" id="rate" min="0" max="1" step="0.01" value="' . $row['rate'] .'"/> 
                                </td>
                                <td>
                                    <input type="submit" name="submitChangeTariff" value="Save"/>
                                    <button type="button" onclick="confirmCancel()">Cancel</button>
                                </td>
                                </tr></form>
                                ';
                            }   else {                                              //Show normal state for other rows
                                if (empty($row['rangeMax'])) $row['rangeMin'] .= '>';

                                echo '
                                <tr><td>' . $row['name'] . '</td>
                                <td>' . $row['rangeMin'] . '</td>
                                <td>' . $row['rangeMax'] . '</td>
                                <td>' . $row['rate'] . '</td>
                                <td><a href="./tariff.php?action=edit&id=' . $row['ID'] . '">
                                    <img src="./images/editIcon.png" alt="edit Icon" id="editIcon"/>
                                </a></td>
                                <td><a href="./tariff.php?action=delete&id=' . $row['ID'] . '">
                                    <img src="./images/trashIcon.png" alt="trash Icon" id="trashIcon"/>
                                </a></td>
                                </tr>
                            ';}
                        }  else {
                      
                            echo '
                            <tr><td>' . $row['name'] . '</td>
                            <td>' . $row['rangeMin'] . '</td>
                            <td>' . $row['rangeMax'] . '</td>
                            <td>' . $row['rate'] . '</td>
                            <td><a href="./tariff.php?action=edit&id=' . $row['ID'] . '">
                                <img src="./images/editIcon.png" alt="edit Icon" id="editIcon"/>
                            </a></td>
                            <td><a href="./tariff.php?action=delete&id=' . $row['ID'] . '">
                                <img src="./images/trashIcon.png" alt="trash Icon" id="trashIcon"/>
                            </a></td>
                            </tr>
                        ';
                        }
                    }
                    echo '</table>';

                    $result->free();
                    
                }
            } else {
                echo 'Loading in data failed';
            }
        ?>

        <button id="addBttn" onclick="showForm()">+</button>

        <form id="addTariff" method="post" action="#" onsubmit="return checkAddTariff()">
            <label for="name">Name:</label>
            <select name="name" id="name">
                <option value="Tier 1">Tier 1</option>
                <option value="Tier 2">Tier 2</option>
                <option value="Tier 3">Tier 3</option>
            </select>

            <label for="type">Type:</label>
            <select name="type" id="type">
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
            </select>

            <label for="rangeMin">Range Minimum:</label>
            <input type="number" name="rangeMin" id="rangeMin" min="0"/>            <!--check of rangeMin < rangeMax-->

            <label for="rangeMax">Range Maximum:</label>
            <input type="number" name="rangeMax" id="rangeMax" min="0" placeholder="/"/>

            <label for="rate">Rate:</label>
            <input type="number" name="rate" id="rate" min="0" max="1" step="0.01"/>

            <p class="error" id="error1"></p>

            <div id="addBttns">
                <input type="submit" name="submitTariff"/>
                <button type="button" id="cancel" onclick="confirmCancel()">Cancel</button>
            </div>
        </form>

        <div id="confirmCancel">
            <p>Anything unsaved will be lost. Are you sure you want to quit?</p>
            <button id="confirmYes" onclick="confirmYes()">Yes</button>
            <button id="confirmNo" onclick="confirmNo()">No</button>
        </div>

        <h2>Customer</h2>
        
        <form id="searchBarForm">
            <input type="text" name="searchBar"/>
            <input type="submit" name="submitSearch"/>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Tariff Name</th>
                <th>Range Min</th>
                <th>Range Max</th>
                <th>Rate</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

        </table>
    </body>
</html>