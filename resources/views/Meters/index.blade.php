<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meter Readings Dashboard</title>
    <style>
        /* Add your CSS styles here */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }

        .panel {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1 1 300px;
            min-width: 300px;
            max-width: 400px;
        }

        .panel h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .panel p {
            margin-bottom: 10px;
        }

        .panel button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .panel button:hover {
            background-color: #0056b3;
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
        <!-- Gas Connection Panel -->
        <div class="panel">
            <h2>Gas Connection</h2>
            <p>Contract Address: {{ $gasConnection->contract_address }}</p>
            <p>Duration: {{ $gasConnection->duration }}</p>
            <p>EAN Number: {{ $gasConnection->ean_number }}</p>
            <p>Meter ID: {{ $gasConnection->meter_id }}</p>
            <button>See Details</button>
        </div>

        <!-- Electricity Connection Panel -->
        <div class="panel">
            <h2>Electricity Connection</h2>
            <p>Contract Address: {{ $electricityConnection->contract_address }}</p>
            <p>Duration: {{ $electricityConnection->duration }}</p>
            <p>EAN Number: {{ $electricityConnection->ean_number }}</p>
            <p>Meter ID: {{ $electricityConnection->meter_id }}</p>
            <button>See Details</button>
        </div>

        <!-- Meter Reading Form Panel -->
        <div class="panel">
            <h2>Enter Meter Reading</h2>
            <form method="POST" action="{{ route('meter_readings.store') }}">
                @csrf
                <label for="present_reading">Present Meter Reading:</label>
                <input type="number" id="present_reading" name="present_reading" required>
                <button type="submit">Save</button>
            </form>
        </div>

        <!-- Meter Readings History Panel -->
        <div class="panel history">
            <h2>Meter Readings History</h2>
            <ul>
                @foreach($meterReadings as $reading)
                    <li>{{ $reading->date }} - {{ $reading->reading }}</li>
                @endforeach
            </ul>
        </div>
        <!-- Meter Readings Chart Panel -->
        <div class="panel">
            <h2>Meter Readings Chart</h2>
            <canvas id="meterReadingsChart"></canvas>
        </div>
    </div>

    <!-- JavaScript for Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var dates = [];
        var readings = [];

        foreach($meterReadings)
        {
            dates.push('{{ $meterReadings->date }}');
            readings.push('{{ $meterReadings->reading }}');
        }


        var ctx = document.getElementById('meterReadingsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Meter Readings',
                    data: readings,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Meter Reading'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
