<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/profile.css" rel="stylesheet" type="text/css"/>
    <link href="/css/header.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Profile</title>

</head>



<body class="body">
    <div class="container-trui">
        <div class="profile-card">
          <img src="/images/profile.jpg" alt="profile" class="profile-image"/>
          <p class="name">John Doe</p>
          <p>Maintainance manager</p>
        </div>

        <div class="details">
          <h5>About</h5>
          <hr>
          <p><div class="text">Employee ID:</div> E231L3</p>
          <p><div class="text">Address:</div> Jan Pieter de Nayerlaan 5, 2860 Sint-Katelijne-Waver</p>
          <p><div class="text">Nationality:</div> Belgian</p>
          <p><div class="text">Status:</div> Single</p>
          <p><div class="text">Sex:</div> Male</p>
          <hr>

          <h5>Contact</h5>
          <hr>
          <p><div class="text">Email:</div> johndoe@gmail.com</p>
          <p><div class="text">Phone:</div> 123 456 789</p>
          <hr/>

          <h5>Bio</h5>
          <hr/>
            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout..</p>
          <hr/>
        </div>

        <div class="right-side">
          <a href="{{route('payList')}}" class="salary-link">
            <div class="salary">
              <h5><u>My salaries</u></h5>
              <p>Click on this box to see all of your payslips from your first to last.</p>
            </div>
          </a>
  
          <a href="{{route('contract')}}">
            <div class="contract">
              <h5 style="margin-bottom: 20px"><u>My contract</u></h5>
              <p><b>From:</b> Energy company</p>
              <p><b>To:</b> John Doe</p>
              <p><b>On the:</b><i> Issue date</i></p>
              <p><b>Start date:</b><i> Start date</i></p>
              <p><b>End date:</b><i> End date</i></p>
            </div>
          </a>
        </div>
    </div>
</body>