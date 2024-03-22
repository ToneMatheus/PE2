<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="/css/dashboard.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <form method="post" action="{{route('submitIndex')}}">
        @csrf
        <label for="meter_id">For meter:</label>
        <select name="meter_id">
            @foreach($results as $result)
                <option>{{ $result->id}}</option>
            @endforeach
        </select>
        <label for='index_value'>Index value:</label>
        <input type='text' name='index_value' id='index_value'>
        <button type='submit'>Submit</button>
    </form>
</body>
</html>