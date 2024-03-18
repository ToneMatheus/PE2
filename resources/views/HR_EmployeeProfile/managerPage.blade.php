<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="/css/manager.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Manager page</title>
    </head>
    <body>
        <h1>Welcome manager</h1><br/>

        <a href="{{route('employeeList')}}"><h5>Employee list</h5></a><br/>

        <h3>Holiday requests</h3>
        <div class="requests">
            <b>Holiday demanded by:</b> dummy<br/>
            <b>On the:</b> DD/MM/YYY<br/>
            <b>From:</b> date <b>To:</b> date<br/>
            <b>Reason for request:</b> I just need a break!<br/>
            <button class="accept">Accept</button><button class="reject">Reject</button>
            <hr/>
            <b>Holiday demanded by:</b> dummy<br/>
            <b>On the:</b> DD/MM/YYY<br/>
            <b>From:</b> date <b>To:</b> date<br/>
            <b>Reason for request:</b> I just need a break!<br/>
            <button class="accept">Accept</button><button class="reject">Reject</button>
        </div>
    </body>
</html>