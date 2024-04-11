<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meter Readings History</title>
    <style>
        /* Add your CSS styles here */
        .container {
            margin: 20px;
        }

        .history ul {
            list-style-type: none;
            padding: 0;
        }

        .history li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Meter Readings History</h1>

        <div class="history">
            <ul>
                @foreach($meterReadings as $reading)
                    <li>{{ $reading->date }} - {{ $reading->reading }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>
