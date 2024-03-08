<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity/Gas Company</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        nav {
            background-color: #444;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        section {
            padding: 20px;
            display: flex;
            justify-content: space-around;
        }
        .tariff-box {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            width: 45%;
            box-sizing: border-box;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Electricity/Gas Company</h1>
    </header>
    <nav>
        <a href="{{ route('test') }}">Home</a>
        <a href="{{ route('customer.portal') }}">Customer Portal</a>
        <a href="{{ route('tarrifs') }}">Tariffs</a>
        <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <section>
        <div class="tariff-box">
            <h2>Gas Tariffs</h2>
            <p>Gas Price per Unit: $0.035</p>
            <p>Special Offer: First 100 Units at $0.025</p>
            <p>Connection Fee: $10/month</p>
        </div>
        <div class="tariff-box">
            <h2>Electricity Tariffs</h2>
            <p>Electricity Price per Unit: $0.15</p>
            <p>Special Offer: Weekends at $0.10 per Unit</p>
            <p>Connection Fee: $15/month</p>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Electricity/Gas Company. All rights reserved.</p>
    </footer>
</body>
</html>
