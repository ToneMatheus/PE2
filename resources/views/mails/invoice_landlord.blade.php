<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two weeks missing warning</title>
</head>
<body>
    <p>Hello,</p>

    <p>You are receiving this invoice because you are registered as the landlord for the address  {{ $user->street }} {{ $user->number }}/{{ $user->box }}, {{ $user->postal_code }} {{ $user->city }}.
    <p>Since there was a gap between the end-of-contract date for your previous tenant, and the start-of-contract date for your
        new tenant in this address, the consumption of electricity and gas during those months has been invoiced to you.</p>

    <p>Entered index value: <b>{{$index_value}}</b></p>
    <p>Consumption: <b>{{$consumption}}</b></p>
    <p>Date: <b>{{$date}}</b></p>
</body>
</html>
