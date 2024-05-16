<!DOCTYPE html>
<html>
<head>
    <title>Confirmation Email</title>
</head>
<body>
    <h1>Thank you for entering your index value for this year!</h1>
    <p>EAN number: <b>{{$EAN}}</b></p>
    <p>Entered index value: <b>{{$index_value}}</b></p>
    <p>Consumption: <b>{{$consumption}}</b></p>
    <p>Date: <b>{{$date}}</b></p>
    <div>
        <p> Please log in to our website or click on the link below to access your profile </p>
        <a href="http://127.0.0.1:8000/Meter_History{{$userID}}">My Profile</a>
    </div>
</body>
</html>
