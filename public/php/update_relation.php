<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "energy_supplier";

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $relationId = $_POST['relationId'];
    $newLeaderId = $_POST['newLeaderId'];

    $stmt = $conn->prepare("UPDATE leader_relations SET leader_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $newLeaderId, $relationId);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
?>
