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
            $notes = explode(',', $detail->notes);
          }
      }
  } 

  $contract = DB::select("select * from employee_contracts where employee_profile_id = $employeeProfileID");//fetching payslip plus contract information

  foreach($contract as $info){
    $start = htmlspecialchars($info->start_date);
    $end = htmlspecialchars($info->end_date);
  }

  //fetch holiday balance for this employee
  $balance = DB::select("select * from balances where employee_profile_id = $employeeProfileID");

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
            <div class="card-header" style="background-color: rgb(148, 146, 146);">
              My notifcations
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Vestibulum at eros</li>
            </ul>
          </div>

          <div class="card">
            <div class="card-header" style="background-color: rgb(148, 146, 146);">
              My documents
            </div>
            <ul class="list-group list-group-flush">
              <a href="{{route('contract')}}"><li class="list-group-item">My contract - from @php echo ("". $start . ""); @endphp to @php echo ("". $end . ""); @endphp</li></a>
              <a href="{{route('payList')}}"><li class="list-group-item">My payslips</li></a>
              <a href="{{route('employeeBenefits')}}"><li class="list-group-item">My benefits</li></a>
            </ul>
          </div>

          <div class="card">
            <div class="card-header" style="background-color: rgb(148, 146, 146);">
              My tasks
            </div>
            <ul class="list-group list-group-flush">
              @foreach($notes as $note)
                @if($note != '')
                <li class="list-group-item" style="display: flex; justify-content: space-between">{{$note}} 
                  <form method="POST" action="{{ route('storeTaskData', ['id' => $userID, 'action' => 'del', 'note' => $note]) }}">
                    @csrf <!-- Include CSRF token for security -->
                    <button type="submit" style="background: none; border: none; padding: 0;"><img src="/images/cancel.png" alt="cancel image" style="width: 15px"/></button>
                  </form></li>
                @endif
              @endforeach
            </ul>
          </div>
        </div>

        <div class="right-side">
          <a href="{{route('request')}}" class="salary-link">
            <div class="salary">
              <h5><u>Request a holiday</u></h5>
              <p>You still have @php echo("<b><u>" . $balance[0]->yearly_holiday_credit . "</u></b>");@endphp holiday credits left</p>
            </div>
          </a>
  
            <div class="news">
              <h5 style="margin-bottom: 20px"><u>Daily news</u></h5>

              <!--<p id="profile-content">$profileContent}}</p>-->
            </div>

            <form method="POST" action="{{ route('storeTaskData', ['id' => $userID, 'action' => 'add', 'note' => '']) }}">
              @csrf
              <h3 style="margin-top: 40px; text-align: center">To do list</h3>
              <input type="text" name="input_data" style="width: 100%; height: 100px;"/><br/>
              <button type="submit" class="btn btn-secondary" style="margin-top: 10px">Add task</button>
            </form>
          
        </div>

    </div>
</body>