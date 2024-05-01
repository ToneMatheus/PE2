<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    
    <p>You have not given use an index value of your meter on address: {{ $user->street }} {{ $user->number }},  {{ $user->city}} {{ $user->postal_code}}</p>
    <p>This was needed 1 week ago, we will give you 1 more week. If you don't enter your index value by then, we will have to take legal actions.</p>
    <p>Link: <a href="#"></a></p>
</body>
</html>