<!DOCTYPE html>
<html>
<head>
    <title>Confirmation Email</title>
</head>
<body>
    <h1>To confirm your registration click on the confirm button</h1>
    <a href="{{ route('activate.account', ['userId' => $id]) }}">Confirm</a>   
</body>
</html>
