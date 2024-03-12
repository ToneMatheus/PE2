<?php
    $roles = [
        'employee' => [
            'description' => 'Employee info here...',
            'people' => 'List of employees...',
            'permissions' => 'What this role can see/do...'
        ],
        'manager' => [
            'description' => 'Manager info here...',
            'people' => 'List of managers...',
            'permissions' => 'Manager permissions...'
        ],
    ];
    // here we can add more roles + this will eventually all be gotten from the DB.

    $selectedRole = $_GET['role'] ?? '';
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
            <select name="role" id="role" onchange="this.form.submit()">
                <option value="">Choose a role...</option>
                <?php foreach ($roles as $roleKey => $roleData): ?>
                    <option value="<?php echo htmlspecialchars($roleKey); ?>" <?php echo $selectedRole === $roleKey ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars(ucfirst($roleKey)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($selectedRole && isset($roles[$selectedRole])): ?>
            <div>
                <h2><?php echo htmlspecialchars(ucfirst($selectedRole)); ?> Description</h2>
                <p><?php echo htmlspecialchars($roles[$selectedRole]['description']); ?></p>
            </div>
        <?php endif; ?>

        <hr>
        <div>
            <h2>All Roles Information</h2>
            <?php foreach ($roles as $roleKey => $roleData): ?>
                <table border="1">
                    <tbody>
                        <tr>
                            <th>Role</th>
                            <td><?php echo htmlspecialchars(ucfirst($roleKey)); ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?php echo htmlspecialchars($roleData['description']); ?></td>
                        </tr>
                        <tr>
                            <th>People</th>
                            <td><?php echo htmlspecialchars($roleData['people']); ?></td>
                        </tr>
                        <tr>
                            <th>Permissions</th>
                            <td><?php echo htmlspecialchars($roleData['permissions']); ?></td>
                        </tr>
                    </tbody>
                </table>
                <br> <!-- Add some space between tables -->
            <?php endforeach; ?>
        </div>
    </body>
</html>
