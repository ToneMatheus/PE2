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
        <a href="{{ route('tarrifs') }}">Tarrifs</a>
        <a href="{{ route('contact') }}">Contact</a>
    </nav>
    
    <section>
        <h2>Welcome to Electricity/Gas Company</h2>
        <p>We provide reliable electricity and gas services to our customers.</p>
        
        <div class="contact-info">
            <h2>Contact Information</h2>
            <p><strong>Address:</strong> 1234 Power Street, Energy Town, Energieland</p>
            <p><strong>Phone:</strong> +1 (555) 123-4567</p>
            <p><strong>Email:</strong> info@electricgasco.com</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Electricity/Gas Company. All rights reserved.</p>
    </footer>
</body>
</html>
