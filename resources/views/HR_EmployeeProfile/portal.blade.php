<!DOCTYPE html>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script>
      $(document).ready(function(){
        var array = ["/images/wind-turbine-tech.png", "/images/profile.jpg", "/images/paycheck.jpg"];
        var slides = $('#slideshow');
        var i = 0;

        function showSlide() {
          slides.fadeOut(500, function() {
            slides.attr('src', array[i]);
            slides.fadeIn(500);
          });
          
          i++;
          if (i >= array.length) {
            i = 0;
          }
        }

        function startSlideShow() {
          setInterval(showSlide, 3000);
        }

        startSlideShow();
      });
  </script>
</head>

<body class="body">
    <div class="container-trui">
        <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Energy supplier</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Jobs</a>
                  </li>
                </ul>
              </div>
            </div>
        </nav>

        <div class="top-page">
          <img src="/images/energy-company.jpg" alt="green energy"/>
          <h1>Are you interested in working with us? Check out our different job offers</h1>
        </div>
        <div class="container">
            <h2 class="mb-3 text-dark" style="text-align: center; margin-top: 40px; margin-bottom: 30px">Join us!</h2>
            <p style="font-size: 18px">
              Discover a brighter, greener future with our commitment to reliability, innovation, and environmental stewardship. Join us in shaping a world where energy meets efficiency and sustainability. 
            </p>


            <h2 style="margin-top: 80px">We are looking for...</h2>
            <div class="container" id="cards-container">
                  <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Electrical engineer</h5>
                    </div>
                    <img src="{{URL('/images/careers_in_electrical_engineering.jpg')}}" class="card-img-top" alt="electrical engineer">
                    <div class="card-body">
                      <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s </p>
                      <a href="{{route('jobDescription')}}" class="card-link">Read more</a>
                    </div>
                  </div>

                  <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Project manager</h5>
                    </div>
                    <img src="{{URL('/images/project_manager.jpg')}}" class="card-img-top" alt="gas">
                    <div class="card-body">
                      <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                      <a href="{{route('jobDescription')}}" class="card-link">Read more</a>
                    </div>
                  </div>

                  <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Wind turbine technician</h5>
                    </div>
                    <img src="{{URL('/images/wind-turbine-tech.png')}}" class="card-img-top" alt="meter">
                    <div class="card-body">
                      <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                      <a href="{{route('jobDescription')}}" class="card-link">Read more</a>
                    </div>
                  </div>
            </div>

            <!--<div class="container" id="cards-container2">
              <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Why choose us?</h5>
                </div>
                <img src="{{URL('/images/question.jpg')}}" class="card-img-top" alt="choose us">
                <div class="card-body">
                  <p class="card-text"> With a focus on innovation and environmental responsibility, we strive to empower our customers with the latest advancements...</p>
                  <a href="#" class="card-link">Read more</a>
                </div>
              </div>

              <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Meters</h5>
                </div>
                <img src="{{URL('/images/help.jpeg')}}" class="card-img-top" alt="meter">
                <div class="card-body">
                  <p class="card-text">Have any questions or are in need of our services or expertise? contact us in the link below</p>
                  <a href="#" class="card-link">Contact us</a>
                </div>
              </div>
            </div>-->
        </div>

        <!--footer -->
        <footer style="background-color: #5299d3; margin-top: 100px;">
          <div class="container p-4">
            <div class="row" style="display: flex; justify-content: space-between">
                <div class="col-md-5">
                    <p>Subscribe to our newsletter to never miss a job offer!</p>
                    <form action="#">
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </div>
                    </form>
                </div>
              <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="mb-1 text-dark">opening hours</h5>
                <table class="table" style="border-color: #666;">
                  <tbody>
                    <tr>
                      <td>Mon - Fri:</td>
                      <td>8am - 9pm</td>
                    </tr>
                    <tr>
                      <td>Sat - Sun:</td>
                      <td>8am - 1am</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright: - Energy company powered by Green Energy.
          </div>
        </footer>
    </div>
</body>
