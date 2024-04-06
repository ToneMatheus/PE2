<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meter History</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    .error-message {
        color: red;
        margin-top: 10px;
    }
</style>
</head>
<body>
    <x-app-layout>
<h1>Meter History</h1>

<form id="meterForm">
    <div>
        <label for="meterType">Select meter type:</label>
        <select id="meterType" name="meterType" required>
            <option value="">Choose meter type</option>
            <option value="gas">Gas</option>
            <option value="electricity">Electricity</option>
        </select>
    </div>
    <div>
        <label for="meterReading">Meter Reading:</label>
        <input type="text" id="meterReading" name="meterReading" pattern="\d{1,5}\.\d" title="Please enter a number with up to 5 digits before the decimal point and 1 digit after it" required>
    </div>
    <button type="button" onclick="addMeterEntry()">Add Reading</button>
</form>

<div id="error-message" class="error-message" style="display: none;"></div>

<table id="meterTable">
    <thead>
        <tr>
            <th>Type</th>
            <th>Date</th>
            <th>Meter Reading</th>
            <th>Amount (Euros)</th>
        </tr>
    </thead>
    <tbody id="meterTableBody">
        <!-- Meter readings will be dynamically added here -->
    </tbody>
</table>

<script>
    var previousReadings = {
        gas: 0,
        electricity: 0
    };

    function addMeterEntry() {
        var meterType = document.getElementById("meterType").value;
        var meterReading = document.getElementById("meterReading").value.trim();

        // Custom validation to check the format of the meter reading
        var regex = /^\d{1,5}\.\d$/;
        if (!regex.test(meterReading)) {
            document.getElementById('error-message').innerText = "Please enter the meter reading in the format 12345.6";
            document.getElementById('error-message').style.display = 'block';
            return;
        }

        meterReading = parseFloat(meterReading);

        if (!meterType || isNaN(meterReading)) {
            document.getElementById('error-message').innerText = "Please fill in all fields.";
            document.getElementById('error-message').style.display = 'block';
            return;
        }

        if (meterReading <= previousReadings[meterType]) {
            document.getElementById('error-message').innerText = "Meter reading should be higher than the previous reading.";
            document.getElementById('error-message').style.display = 'block';
            return;
        }

        var table = document.getElementById("meterTable").getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);
        var cellType = newRow.insertCell(0);
        var cellDate = newRow.insertCell(1);
        var cellReading = newRow.insertCell(2);
        var cellAmount = newRow.insertCell(3);

        var currentDate = new Date().toISOString().slice(0, 10).split('-').reverse().join('-');

        cellType.innerHTML = meterType;
        cellDate.innerHTML = currentDate;
        cellReading.innerHTML = meterReading.toFixed(1);

        var difference = meterReading - previousReadings[meterType];
        var rate = (meterType === 'gas') ? 12 : 12; // Adjust rates as needed
        var amount = difference * rate;
        cellAmount.innerHTML = amount.toFixed(2);

        previousReadings[meterType] = meterReading;

        // Clear form fields after adding entry
        document.getElementById("meterType").value = "";
        document.getElementById("meterReading").value = "";

        // Hide error message if any
        document.getElementById('error-message').style.display = 'none';
    }
</script>
</x-app-layout>
</body>
</html>
