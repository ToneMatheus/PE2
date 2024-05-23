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
    
    <p>This is a reminder that the index values of your meter: {{ $user->street }} {{ $user->number }},  {{ $user->city}} {{ $user->postal_code}}</p>
    <p>is due in 1 week. Please enter your index values.</p>
    <p><a href="{{$domain}}/Meter_History?token={{$encryptedTempUserId}}">Enter your index value here</a></p>

    <p>If you already have done this, you can ignore this mail.</p>
</body>
</html>