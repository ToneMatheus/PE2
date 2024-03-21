<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Welcome to the Consumption page!</h1><br>
        <p>To view your consumption history</p>
        <a href="{{ route('Meter_History') }}"><button>Meter History</button></a>
    </div>
    <div>
        <h3>If you would like to enter the meter readings</h3>
        <a href="{{ route('Consumption_Reading') }}"><button>Consumption Reading</button></a>
    </div>
</body>
</html>
