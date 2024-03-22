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

    $relationsQuery = "
        SELECT lr.id, lr.leader_id, CONCAT(l.first_name, ' ', l.last_name) AS leader_name, 
            CONCAT(e.first_name, ' ', e.last_name) AS employee_name, lr.relation
        FROM leader_relations lr
        JOIN users l ON lr.leader_id = l.id
        JOIN users e ON lr.employee_id = e.id";
    $relationsResult = $conn->query($relationsQuery);

    $leadersQuery = "SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM users";
    $leadersResult = $conn->query($leadersQuery);


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

        {{-- <form action="" method="GET">
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
        </form> --}}

        <table>
            <tr>
                <th>role</th>
                <th>permissions</th>
            </tr>
            
            <tr>
                <td>customer</td>
                <td>registration, login, profile, all customer date overviews (personal data and tarrifs), their invoices</td>
            </tr>

            <tr>
                <td>employee</td>
                <td>holiday form, personal payment info, their payslips, role hierarchy</td>
            </tr>

            <tr>
                <td>manager</td>
                <td>all of the above + hierarchy edit availability and holiday requests</td>
            </tr>

            <tr>
                <td>boss</td>
                <td>all of the above + holiday requests that have been sent through by managers, all company income, overview of balance...</td>
            </tr>
        </table>
        <hr>
{{-- 
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
         --}}

        <hr>
        <form id="editRelationForm">
            <label for="relationListbox">Select a leader-employee relation:</label>
            <select id="relationListbox" onchange="selectRelation()">
                <option value="0">Select a relation...</option>
                <?php
                    if ($relationsResult->num_rows > 0) {
                        while ($row = $relationsResult->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "' data-leader-id='" . $row["leader_id"] . "'>" . $row["leader_name"] . " is " . $row["relation"] . " of " . $row["employee_name"] . "</option>";
                        }
                    }
                ?>
            </select>
            <br>
            <select id="leaderDropdown" disabled>
                <?php
                    if ($leadersResult->num_rows > 0) {
                        while ($row = $leadersResult->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                ?>
            </select>
            <label for="employeeTextbox">is</label>
            <input type="text" id="employeeTextbox" disabled>

            <br>
            <button type="button" onclick="enableEditing()">Edit</button>
            <button type="button" onclick="saveChanges()" disabled>Save</button>
        </form>
        <script>
            function selectRelation() {
                var listbox = document.getElementById("relationListbox");
                var leaderDropdown = document.getElementById("leaderDropdown");
                var employeeTextbox = document.getElementById("employeeTextbox");


                if (listbox.selectedIndex > 0) { 
                    var selectedOption = listbox.options[listbox.selectedIndex];
                    var leaderId = selectedOption.getAttribute("data-leader-id"); 

                    var parts = selectedOption.text.split(" is ");
                    var employeeName = parts.length > 1 ? parts[parts.length - 1] : "";

                    for (var i = 0; i < leaderDropdown.options.length; i++) {
                        if (leaderDropdown.options[i].value === leaderId) {
                            leaderDropdown.selectedIndex = i;
                            break; 
                        }
                    }

                   employeeTextbox.value = employeeName.trim();
                } else {
                    leaderDropdown.selectedIndex = 0;
                    employeeTextbox.value = "";
                }
            }

            function enableEditing() {
                document.getElementById("leaderDropdown").disabled = false;
                document.getElementById("editRelationForm").querySelector("button[type='button']:last-child").disabled = false;
            }
            function saveChanges() {
                var relationId = document.getElementById("relationListbox").value;
                var newLeaderId = document.getElementById("leaderDropdown").value;

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "{{ route('relations.update') }}", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");
                xhr.onreadystatechange = function () {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        alert("Relation updated successfully.");
                        
                        var fetchXhr = new XMLHttpRequest();
                        fetchXhr.open("GET", "/relations", true);
                        fetchXhr.onload = function() {
                            if (fetchXhr.status === 200) {
                                var relations = JSON.parse(fetchXhr.responseText);
                                var relationListbox = document.getElementById("relationListbox");
                                var leaderDropdown = document.getElementById("leaderDropdown");

                                relationListbox.innerHTML = '<option value="0">Select a relation...</option>';

                                relations.forEach(function(relation) {
                                    var optionText = relation.leader_name + " is " + relation.relation +" of " + relation.employee_name
                                    var option = new Option(optionText, relation.id);
                                    option.setAttribute("data-leader-id", relation.leader_id);
                                    relationListbox.add(option);
                                });

                                leaderDropdown.selectedIndex = 0;
                                leaderDropdown.disabled = true;
                                document.getElementById("employeeTextbox").value = "";
                                document.getElementById("editRelationForm").querySelector("button[type='button']:last-child").disabled = true;
                            }
                        };
                        fetchXhr.send();
                    }
                };
                xhr.send("relationId=" + relationId + "&newLeaderId=" + newLeaderId);

                document.getElementById("leaderDropdown").disabled = true;
                document.getElementById("editRelationForm").querySelector("button[type='button']:last-child").disabled = true;
            }
        </script>

        

        
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
