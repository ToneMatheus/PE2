<!DOCTYPE html>
<html>
    <head>
        <link href="/css/roles.css" rel="stylesheet" type="text/css"/>
        <title>Team Names</title>
    </head>
    <body>
        <h1>Team Names</h1>
        <table>
            <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Manager</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                    <tr>
                        <td>{{ $team->teamName }}</td>
                        <td>{{ $team->first_name }} {{ $team->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>