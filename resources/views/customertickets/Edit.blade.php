<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Company Ticket Management System</title>
    <style>
        /* Basic styles for the ticket management system */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8; /* Light blue background */
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #1e4168; /* Dark blue header */
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            text-align: center;
            background-color: #4b7bbd;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: #4b7bbd; /* Medium blue navbar */
            display: inline-block; /* Use inline-block to center */
            border-radius: 5px; /* Rounded corners */
        }

        nav ul li {
            display: inline; /* Display items inline */
        }

        nav ul li a {
            display: inline-block; /* Display items inline-block */
            color: #fff; /* White text */
            padding: 20px;
            text-decoration: none;
        }

        nav ul li a:hover {
            background-color: #326599; /* Darker blue on hover */
        }

        main {
            flex: 1; /* Take remaining space */
            padding: 20px;
            background-color: #fff; /* White background */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
            overflow-y: auto; /* Add vertical scrollbar if needed */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4b7bbd; /* Medium blue for table header */
            color: #fff;
        }

        footer {
            background-color: #1e4168; /* Dark blue footer */
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px; /* Rounded corners */
        }

        .h1 {
            color: #1e4168;
        }

        /* Style for input fields */
        input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        /* Style for submit button */
        .submit-btn {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Energy Company Ticket Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="{{ route('test') }}">Home</a></li>
            <li><a href="{{ route('customertickets.index') }}">Tickets History</a></li>
            <li><a href="#">Open Ticket</a></li>
            <li><a href="#">Update Ticket</a></li>
            <li><a href="#">Escalate Ticket</a></li>
        </ul>
    </nav>
    <main>
        <h1 class="h1">Edit Tickets</h1>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Issue</th>
                <th>Description</th>
                <th>Submit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tickets as $ticket)
                <tr>
                

                    <td>{{ $ticket->ID }}</td>
                    <td><input type="text" value="{{ $ticket->name }}"></td>
                    <td><input type="text" value="{{ $ticket->email }}"></td>
                    <td><input type="text" value="{{ $ticket->issue }}"></td>
                    <td><input type="text" value="{{ $ticket->description }}"></td>
                    <td><button class="submit-btn" onclick="submitData()">Submit</button></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Energy Company. All rights reserved.</p>
    </footer>
</div>
</body>
</html>