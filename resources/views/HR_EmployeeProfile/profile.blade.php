<!DOCTYPE html>
<html>
@php
  $userID = Auth::id();

  $users = DB::select("select * from users where id = $userID");//selecting employee information

  if (!empty($users)) {
      foreach ($users as $user) {
          $lastName = htmlspecialchars($user->last_name);
          $firstName = htmlspecialchars($user->first_name);
          $email = htmlspecialchars($user->email);
          $phone = htmlspecialchars($user->phone_nbr);
          $employeeProfileID = htmlspecialchars($user->employee_profile_id);
          $nationality = htmlspecialchars($user->nationality);
          $title = htmlspecialchars($user->title);
        
          //fetching user address from the db
          $employee_profile = DB::select("select * from employee_profiles where id = $userID");
          $emp_id = $employee_profile[0]->id;
          $user = DB::select("select * from users where employee_profile_id = $emp_id");
          //$user_id = $user[0]->id;
          $address = DB::select("select * from customer_addresses where user_id = $userID");
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
            $notes = htmlspecialchars($detail->notes);
            $job = htmlspecialchars($detail->job);
            $notes = explode(',', $detail->notes);
          }
      }

      $contract = DB::select("select * from employee_contracts where employee_profile_id = $employeeProfileID");//fetching payslip plus contract information
      foreach($contract as $info){
        $start = htmlspecialchars($info->start_date);
        $end = htmlspecialchars($info->end_date);
      }

      //fetch holiday balance for this employee
      $balance = DB::select("select * from balances where employee_profile_id = $employeeProfileID");

      $team = DB::select("select * from team_members inner join teams on team_members.team_id = teams.id where user_id = $userID");
      $team_name = htmlspecialchars($team[0]->team_name);

      //the team leader
      $team_leader = DB::select("SELECT * FROM team_members INNER JOIN teams ON team_members.team_id = teams.id WHERE teams.team_name = ? AND team_members.is_manager = 1", [$team_name]);
      $team_leader_details = DB::select("select * from users where id = " . $team_leader[0]->user_id);
  } 


@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/profile.css" rel="stylesheet" type="text/css"/>
    <link href="/css/header.css" rel="stylesheet" type="text/css"/>
    <title>{{$firstName}}'s profile</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body class="body">
    <div class="container emp-profile">
        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-img">
                        <img src="/images/profile.jpg" alt="profile" class="profile-image"/>
                        <div class="file btn btn-lg btn-primary">
                            Change photo
                            <input type="file" name="file"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                                <h5>
                                    @php echo ("" . $firstName . " " . $lastName); @endphp
                                </h5>
                                <p class="proile-rating">DEPARTMENT : <span>@php echo($team_name); @endphp</span></p>
                            
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#" role="tab" aria-controls="home" aria-selected="true">About</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-work">
                        <p>WORK STATUS</p>
                        <i>Role:</i><br/>Employee<br/>
                        <i>Reports to:</i><br/>@php echo ($team_leader_details[0]->first_name . " " . $team_leader_details[0]->last_name);@endphp<br/>
                        <p>CONTACT</p>
                        <i>Email:</i><br/>
                        {{$email}}<br/>
                        <i>Phone:</i><br/>
                        {{$phone}}
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content profile-tab" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>User Id</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p>{{$userID}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Full name</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p>@php echo ("" . $firstName . " " . $lastName); @endphp</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Address</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p>{{$userAddress}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Nationality</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p>{{$nationality}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Profession</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p>{{$job}}</p>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>           
    </div>
</body>
</html>