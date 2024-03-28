<?php
    if (isset($_GET['roleName'])) {
        $roleName = $_GET['roleName'];
    
        // role_name -> role_id
        $stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = ?");
        $stmt->bind_param("s", $roleName);
        $stmt->execute();
        $result = $stmt->get_result();
        $role = $result->fetch_assoc();
        
        $roleId = $role['id'];
    
        // role_id -> user_id
        $userIds = [];
        $stmt = $conn->prepare("SELECT user_id FROM user_roles WHERE role_id = ? AND active = 1");
        $stmt->bind_param("i", $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $userIds[] = $row['user_id'];
        }
    
        // user_id -> names
        $userNames = [];
        foreach ($userIds as $userId) {
            $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ? AND active = 1");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($user = $result->fetch_assoc()) {
                $userNames[] = $user['first_name'] . " " . $user['last_name'];
            }
        }
    
        // Return
        echo json_encode($userNames);
    } else {
        echo json_encode([]);
    }
?>