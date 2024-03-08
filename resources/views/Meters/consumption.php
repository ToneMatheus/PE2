<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utility Consumption Calculator</title>
    <link rel="stylesheet" href="../../css/consumption.css">
</head>
<body>
    <h2>Consumption Page</h2>
    <div class="container">
        <div class="form-container">
            <h2>Electricity Consumption</h2>
            <form method="post" action="">
                <label for="startValueElectricity">Starting Value (kWh):</label><br>
                <input type="number" id="startValueElectricity" name="startValueElectricity" required><br>

                <label for="currentValueElectricity">Current Value (kWh):</label><br>
                <input type="number" id="currentValueElectricity" name="currentValueElectricity" required><br>

                <button type="submit" name="calculateElectricity">Calculate Electricity Consumption</button>
            </form>
            <?php
        // Check if electricity form is submitted
        if(isset($_POST['calculateElectricity'])) {
            $startValueElectricity = $_POST['startValueElectricity'];
            $currentValueElectricity = $_POST['currentValueElectricity'];
            $ratePerKWH = 12; // Euros per kWh

            // Function to calculate electricity consumption
            function calculateElectricityConsumption($start, $current, $rate) {
                return ($current - $start) * $rate;
            }

            // Calculate electricity consumption
            $electricityConsumption = calculateElectricityConsumption($startValueElectricity, $currentValueElectricity, $ratePerKWH);

            // Display the final electricity consumption
            echo "<p>Electricity consumption: " . $electricityConsumption . " euros</p>";
        }
        ?>
        </div>

        <div class="form-container">
            <h2>Gas Consumption</h2>
            <form method="post" action="">
                <label for="startValueGas">Starting Value (m³):</label><br>
                <input type="number" id="startValueGas" name="startValueGas" required><br>

                <label for="currentValueGas">Current Value (m³):</label><br>
                <input type="number" id="currentValueGas" name="currentValueGas" required><br>

                <button type="submit" name="calculateGas">Calculate Gas Consumption</button>
            </form>
            <?php
        // Check if gas form is submitted
        if(isset($_POST['calculateGas'])) {
            $startValueGas = $_POST['startValueGas'];
            $currentValueGas = $_POST['currentValueGas'];
            $ratePerM3 = 12; // Euros per m³

            // Function to calculate gas consumption
            function calculateGasConsumption($start, $current, $rate) {
                return ($current - $start) * $rate;
            }

            // Calculate gas consumption
            $gasConsumption = calculateGasConsumption($startValueGas, $currentValueGas, $ratePerM3);

            // Display the final gas consumption
            echo "<p>Gas consumption: " . $gasConsumption . " euros</p>";
        }
        ?>
        </div>
    </div>
</body>
</html>
