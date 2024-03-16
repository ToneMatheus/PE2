<?php
    // DONE: connect to DB
    // DONE: get all role_name from roles table -> fill dropdown.
    // DONE: depending on selected option (dropdown) -> display correct info:
    // - role: role_name directly from dropdown 
    // - role description: (if else) pre written (not from DB)
    // - permissions: ^^ together with this -> maybe later table with all pages + table to specify role+page for viewing? (TODO change to pages)
    // - people: 
    //   + get role_id depending on selected role (role_name) from roles table
    //   + get user_id's with role_id from user_roles table (only active)
    //   + get users first_name + last_name using user_id from users table (only active)
    //    -> dont show people here?

    // TODO: show a table with who manages who. and be able to change that information.
    // TODO: hierarchy structure ??

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "energy_supplier";

    $conn = mysqli_connect($host, $user, $password, $db);

    if (!$conn)
    {
        die("Connection to database failed.");
    }

    $managingQuery = "SELECT * FROM leader_relations";
    $managingResult = $conn->query($managingQuery);

    $rolesQuery = "SELECT id, role_name FROM roles";
    $rolesResult = $conn->query($rolesQuery);

?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/css/roles.css" rel="stylesheet" type="text/css"/>
        <title>Hierarchy/Roles</title>
    </head>
    <body>
        <header>
            
        </header>
        <h1>Hierarchy/Roles</h1>

        <form action="" method="GET">
            <label for="role">Select a role:</label>
            <select name="role" id="role" onchange="updateRoleInfo()">
                <option value="0">Select a role...</option>
                <?php
                    if ($rolesResult->num_rows > 0)
                    {
                        while($row = $rolesResult->fetch_assoc())
                        {
                            echo "<option value='" . $row["id"] . "'>" . $row["role_name"] . "</option>";
                        }
                    }
                    else
                    {
                        echo "<option value=''>No roles found</option>";
                    }
                ?>
            </select>
        </form>
        <hr>

        <table id="roleInfoTable">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Role</td>
                <td id="roleName"></td>
            </tr>
            <tr>
                <td>Role Description</td>
                <td id="roleDescription">-</td>
            </tr>
            <tr>
                <td>Permissions</td>
                <td id="rolePermissions">-</td>
            </tr>
            <tr>
                <td>People</td>
                <td id="rolePeople">-</td>
            </tr>
        </table>
        <hr>
        

        
        <script>
            var roleDescriptions = [
                "Description for customer role",
                "Description for employee role",
                "Description for manager role",
                "Description for boss role"
            ];
            var rolePermissions = [
                ["Permission 1.1", "Permission 1.2", "Permission 1.3"],
                ["Permission 2.1", "Permission 2.2"],
                ["Permission 3.1", "Permission 3.2", "Permission 3.3"],
                ["Permission 4.1"]
            ];

            function updateRoleInfo()
            {
                var select = document.getElementById("role");
                var selectedIndex = select.selectedIndex;
                var roleName = select.options[selectedIndex].text;

                // role
                document.getElementById("roleName").textContent = roleName;

                // role description
                var description = roleDescriptions[selectedIndex - 1];
                document.getElementById("roleDescription").textContent = description ? description : "Description not available";
                
                // role permission
                var permissionsArray = rolePermissions[selectedIndex - 1];
                var permissions = permissionsArray ? permissionsArray.join(", ") : "Permissions not available";
                document.getElementById("rolePermissions").textContent = permissions;

                // role people
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '/fetch-users-by-role?roleName=' + encodeURIComponent(roleName));
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var userNames = JSON.parse(xhr.responseText);
                        document.getElementById("rolePeople").textContent = userNames.join(", ");
                    } else {
                        document.getElementById("rolePeople").textContent = "Failed to fetch users";
                    }
                };
                xhr.send();

            }
        </script>
    </body>
</html>
