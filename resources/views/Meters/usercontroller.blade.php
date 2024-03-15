<!DOCTYPE html>
<html>
<head>
    <title>User Data</title>
</head>
<body>
    

    <!-- Form to add meter reading -->
    <form method="post" action="{{ url('/user') }}">
        @csrf
        <label for="meter_reading">Meter Reading:</label>
        <input type="number" name="meter_reading" id="meter_reading" required>
        <label for="reading_date">Reading Date:</label>
        <input type="date" name="reading_date" id="reading_date" required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
