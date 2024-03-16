<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "energy_supplier";

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $relationsQuery = "
        SELECT lr.id, lr.leader_id, CONCAT(l.first_name, ' ', l.last_name) AS leader_name, 
            CONCAT(e.first_name, ' ', e.last_name) AS employee_name
        FROM leader_relations lr
        JOIN users l ON lr.leader_id = l.id
        JOIN users e ON lr.employee_id = e.id";
    $result = $conn->query($relationsQuery);

    $relations = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $relations[] = $row;
        }
    }

    echo json_encode($relations);

    $conn->close();
?>
