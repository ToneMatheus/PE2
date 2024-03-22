<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/manager.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Managers page</title>

    @php
        $managers = DB::select("select * from users left join user_roles on user_roles.user_id = users.id left join roles on roles.id = user_roles.role_id where roles.role_name = \"Manager\"");
    
        echo("<h1>Managers</h1>");

        foreach($managers as $manager){
            echo("<div>");
                echo("<a href=\"" . route('managerPage', ['manager_id' => $manager->user_id]) . "\" style=\"font-size: 25px\">$manager->first_name</a>");
            echo("</div>");
        }
    @endphp
</body>
</html>