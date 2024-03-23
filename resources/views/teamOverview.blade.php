<!DOCTYPE html>
<html>
<head>
    <title>Team Names</title>
</head>
<body>
    <h1>Team Names</h1>
    <ul>
        @foreach($teams as $team)
            <li>{{ $team->name }}</li>
        @endforeach
    </ul>
</body>
</html>