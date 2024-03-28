<!DOCTYPE html>
<html>
<head>
    <title>Consumption Reading</title>
</head>
<body>
    <h1>Consumption Reading Page</h1>
    <form method="post" action="{{ url('/user') }}">
        @csrf
        <label for="meter_reading">Meter Reading:</label>
        <input type="text" name="meter_reading" id="meter_reading" pattern="\d{5}\.\d" maxlength="7" title="Please enter 5 digits followed by a decimal point and then another digit" required>
        <label for="reading_date">Reading Date:</label>
        <input type="date" name="reading_date" id="reading_date" max="<?php echo date('Y-m-d'); ?>" required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
