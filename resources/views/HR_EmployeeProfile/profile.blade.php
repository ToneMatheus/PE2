<!DOCTYPE html>
@php
  $userID = 1;//this is to be changed by the real id!

  $users = DB::select("select * from users where id = $userID");//selecting employee information

  if (!empty($users)) {
      foreach ($users as $user) {
          $lastName = htmlspecialchars($user->last_name);
          $firstName = htmlspecialchars($user->first_name);
          $email = htmlspecialchars($user->email);
          $phone = htmlspecialchars($user->phone_nbr);
          $addressID = htmlspecialchars($user->address_id);
          $employeeProfileID = htmlspecialchars($user->employee_profile_id);
        
          //fetching user address from the db
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
          <img src="/images/profile.jpg" alt="profile" class="profile-image"/>
          <p class="name">@php echo ("" . $firstName . " " . $lastName); @endphp</p>
          <p>@php echo ($job . "\n" . $dept); @endphp</p>
        </div>

        <div class="details">
          <h5>About</h5>
          <hr>
          <p><div class="text">Address:</div>@php echo $userAddress; @endphp</p>
          <p><div class="text">Nationality:</div>@php echo $nationality @endphp</p>
          <p><div class="text">Sex:</div>@php echo $sex @endphp</p>
          <hr>

          <h5>Contact</h5>
          <hr>
          <p><div class="text">Email:</div>@php echo $email @endphp</p>
          <p><div class="text">Phone:</div>@php echo $phone @endphp</p>
          <hr/>

          <h5>Notes</h5>
          <hr/>
            <p>@php echo $notes @endphp</p>
          <hr/>
        </div>

        <div class="right-side">
          <a href="{{route('payList')}}" class="salary-link">
            <div class="salary">
              <h5><u>My salaries</u></h5>
              <p>Click on this box to see all of your payslips from your first to last.</p>
            </div>
          </a>
  
          <!--Not first checking if the the contract table is empty because there shouldn't be an employee in the company that doesn't have a contract-->
          <a href="{{route('contract')}}">
            <div class="contract">
              <h5 style="margin-bottom: 20px"><u>My contract</u></h5>
              <p><b>From:</b> Energy company</p>
              <p><b>To: </b>@php echo ("" . $firstName . " " . $lastName); @endphp</p>
              <p><b>Start date: </b><i>@php echo ("". $start . ""); @endphp</i></p>
              <p><b>End date: </b><i>@php echo ("". $end . ""); @endphp</i></p>
            </div>
          </a>
        </div>
    </div>
</body>