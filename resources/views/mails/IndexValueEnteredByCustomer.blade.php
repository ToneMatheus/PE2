<!DOCTYPE html>
<html>
<head>
    <title>Confirmation Email</title>
</head>
<body>
    <h1>Thank you for entering your index values for this year!</h1>
    <p>EAN number: <b>{{$EAN}}</b></p>
    <p>Entered index value: <b>{{$index_value}}</b></p>
    <p>Consumption: <b>{{$consumption}}</b></p>
    <p>Date: <b>{{$date}}</b></p>
    <a href="{{$domain}}/Meter_History?token={{$token}}">See your data here</a>
</body>
</html>
