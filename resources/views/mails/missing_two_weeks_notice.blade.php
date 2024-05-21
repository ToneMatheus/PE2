<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two weeks missing warning</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    
    <p>This is a warning that the index values of your meter: {{ $user->street }} {{ $user->number }},  {{ $user->city}} {{ $user->postal_code}}</p>
    <p>has not been entered for two weeks past the due date. As such, tomorrow, an employee will be sent to your address to manually check the 
        meter reading. And, a fee of {{ $fee }} will be added to your next invoice.
    </p>
</body>
</html>