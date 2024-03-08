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
        <a href="{{ route('customertickets.index') }}">Customer Tickets</a>
        <a href="{{ route('tarrifs') }}">Tarrifs</a>
        <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <section>
        <!-- <h2>Welcome to Our Company</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vehicula enim at justo aliquet consequat. In hac habitasse platea dictumst. Nullam at consectetur odio, at dictum velit. Fusce posuere justo id sapien fringilla, non consectetur justo suscipit.</p>
        <p>Sed vitae velit vitae nisl tempor feugiat. Vestibulum vitae enim mi. Nullam auctor quam at augue dapibus, nec mattis metus posuere.</p>
    -->
        <?php
            use Illuminate\Support\Facades\DB;

            // Your database connection check
            try {
                // Check if the database connection is successful
                DB::connection()->getPdo();
            
                echo "Successfully connected to the database. Database name is: " . DB::connection()->getDatabaseName();
            } catch (\Exception $e) {
                // If there's an exception, print the error message
                die("Could not connect to the database. Error: " . $e->getMessage());
            }
        ?>
    </section> 
    <footer>
        <p>&copy; 2024 Electricity/Gas Company. All rights reserved.</p>
    </footer>
</body>
</html>
