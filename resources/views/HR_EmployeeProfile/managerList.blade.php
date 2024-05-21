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
        $managers = DB::select("select * from team_members left join teams on team_members.team_id = teams.id left join users on team_members.user_id = users.id where team_members.is_manager = 1");
    
        echo("<h1>Managers</h1>");

        foreach($managers as $manager){
            echo("<div>");
                echo("<a href=\"" . route('managerPage', ['manager_id' => $manager->user_id]) . "\" style=\"font-size: 25px\">$manager->first_name $manager->last_name manages $manager->team_name</a>");
            echo("</div>");
        }
    @endphp
</body>
</html>