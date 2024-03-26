<!DOCTYPE html>
@php
  $userID = 3;//this is to be changed by the real id!

  $users = DB::select("select * from users where id = $userID");//selecting employee information

  if (!empty($users)) {
      foreach ($users as $user) {
          $lastName = htmlspecialchars($user->last_name);
          $firstName = htmlspecialchars($user->first_name);
          $email = htmlspecialchars($user->email);
          $phone = htmlspecialchars($user->phone_nbr);
          $employeeProfileID = htmlspecialchars($user->employee_profile_id);
        
          //fetching user address from the db
          $employee_profile = DB::select("select * from employee_profiles where id = $userID");
          $emp_id = $employee_profile[0]->id;
          $user = DB::select("select * from users where employee_profile_id = $emp_id");
          $user_id = $user[0]->id;
          $address = DB::select("select * from customer_addresses where user_id = $user_id");
          $addressID = htmlspecialchars($address[0]->address_id);
          $emp_address = DB::select("select * from addresses where id = $addressID");

          $address = DB::select("select * from addresses where id = $addressID");
          foreach ($address as $add){
            $street = htmlspecialchars($add->street);
            $num = htmlspecialchars($add->number);
            $pC = htmlspecialchars($add->postal_code);
            $box = htmlspecialchars($add->box);
            $city = htmlspecialchars($add->city);
            $province = htmlspecialchars($add->province);

            $userAddress = "" . $street . " " . $num . " " . $box . ", " . $pC . " " . $city . ". " . $province . ".";//joining the address into one long address
          }

          $empDetails = DB::select("select * from employee_profiles where id = $employeeProfileID");
          foreach ($empDetails as $detail){
            $nationality = htmlspecialchars($detail->nationality);
            $sex = htmlspecialchars($detail->sex);
            $notes = htmlspecialchars($detail->notes);
            $job = htmlspecialchars($detail->job);
            $dept = htmlspecialchars($detail->department);
          }
      }
  } 

  $contract = DB::select("select * from employee_contracts where employee_profile_id = $employeeProfileID");//fetching payslip plus contract information

  foreach($contract as $info){
    $start = htmlspecialchars($info->start_date);
    $end = htmlspecialchars($info->end_date);
  }


@endphp

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
          <div class="top"><img src="/images/profile.jpg" alt="profile" class="profile-image"/>
          <p class="name">@php echo ("" . $firstName . " " . $lastName); @endphp</p>
          <p>@php echo ($job . "\n" . $dept); @endphp</p><br/></div>
          <h5>About</h5>
          <p>Address:<br/>@php echo $userAddress; @endphp</p>
          <p>Nationality:<br/>@php echo $nationality @endphp</p>
          <p>Sex:<br/>@php echo $sex @endphp</p>
          <hr>
          <h5>Contact</h5>
          <p>Email:<br/>@php echo $email @endphp</p>
          <p>Phone:<br/>@php echo $phone @endphp</p>

        </div>

        <div class="middle"> 
          <div class="card">
            <div class="card-header">
              My notifcations
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Vestibulum at eros</li>
            </ul>
          </div>

          <div class="card">
            <div class="card-header">
              My tasks
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Vestibulum at eros</li>
            </ul>
          </div>

          <div class="card">
            <div class="card-header">
              My documents
            </div>
            <ul class="list-group list-group-flush">
              <a href="{{route('contract')}}"><li class="list-group-item">My contract - from @php echo ("". $start . ""); @endphp to @php echo ("". $end . ""); @endphp</li></a>
              <li class="list-group-item"><a href="{{route('payList')}}">My payslips</a></li>
              <a href="{{route('employeeBenefits')}}"><li class="list-group-item">My benefits</li></a>
            </ul>
          </div>
        </div>

        <div class="right-side">
          <a href="{{route('request')}}" class="salary-link">
            <div class="salary">
              <h5><u>Request a holiday</u></h5>
              <p>Click on this box to see all of your payslips from your first to last.</p>
            </div>
          </a>
  
            <div class="news">
              <h5 style="margin-bottom: 20px"><u>Daily news</u></h5>

              <p>What's on the menu?<br/>fish and chips</p>
            </div>
        </div>

    </div>
</body>