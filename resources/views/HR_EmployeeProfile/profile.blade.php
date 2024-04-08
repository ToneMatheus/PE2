<!DOCTYPE html>
<html>
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
                    <a href="@php route('profile.edit'); @endphp"><input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/></a>
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