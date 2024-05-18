<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter index value</title>
    <style>
        a {
            text-decoration: none;
            padding: 10px;
            background-color: rgb(35, 35, 205);
        }

        a:hover {
            background-color: rgb(18, 18, 106);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Hello, {{$user[0]->first_name}} {{$user[0]->last_name}}!</h1>

    <p>You are receiving this e-mail because today is the <b>first or second year</b> since your meters were installed, or an employee was sent to your place to examine the index values.</br>
        Therefore, we ask you to enter the index value(s) by clicking on the link below.</p>
    
    <p>Please be sure to enter your index values(s) as soon as possible!</p>
    <p><a href="http://localhost:8000/Meter_History?token={{$encryptedTempUserId}}" style="color:white;">Enter your index value here</a></p>
    </p>
    <p>Thank you for using our service! </p>
</body>
</html>
