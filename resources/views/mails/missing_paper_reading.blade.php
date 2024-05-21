<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing meter reading warning</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    
    <p>You are receiving this email because you have opted to send us your meter readings through paper.</p>
    <p>This is a reminder that the index values of your meter: {{ $user->street }} {{ $user->number }},  {{ $user->city}} {{ $user->postal_code}}</p>
    <p>was due one week ago, and has still not been received. Please send us your meter index value as soon as possible.</p>

    <p>If the letter is not received by us within a week from today, we will be sending an employee to your address in order to enter the index values manually.
    </p>

    <p>If you already have done this, you can ignore this mail.</p>
</body>
</html>