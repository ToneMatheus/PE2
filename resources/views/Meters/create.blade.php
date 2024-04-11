<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Meter Reading</title>
    <style>
        /* Add your CSS styles here */
        .container {
            margin: 20px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container label {
            margin-bottom: 10px;
        }

        .form-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Enter Meter Reading</h1>

            <form method="POST" action="{{ route('meter_readings.store') }}">
                @csrf
                <label for="present_reading">Present Meter Reading:</label>
                <input type="number" id="present_reading" name="present_reading" required>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</body>
</html>
