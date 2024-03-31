<!DOCTYPE html>
<html>
    <head>
        <title>{{$employee->first_name}} {{$employee->last_name}} Profile</title>
        <meta charset="utf-8"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/editEmployee.css') }}"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script></script>
    </head>
    <body>
        <h1>{{$employee->first_name}} {{$employee->last_name}} Profile</h1>
        <p>E_ID: {{$employee->employee_profile_id}}</p>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Personal Info
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="post" action="{{ route('employees.edit.personal', ['eID' => $employee->employee_profile_id]) }}">
                            @csrf
                            <div class="row">
                                <div class="col-3">
                                    <p>
                                        <label for="firstName">First Name:</label>
                                        <input type="text" id="firstName" name="firstName" value="{{$employee->first_name}}" required/>
                                    </p>
                                    <p>
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" value="{{$employee->last_name}}" required/>
                                    </p>
                                    <p>
                                        <label for="title">Honorific:</label>
                                        <select name="title" id="title">
                                            <option value="Mr" @if($employee->title == 'Mr') selected @endif>Mr.</option>
                                            <option value="Ms" @if($employee->title == 'Ms') selected @endif>Ms.</option>
                                            <option value="X" @if($employee->title == 'X') selected @endif>Mx.</option>
                                        </select>
                                    </p>
                                </div>

                                <div class="col-3">
                                    <p>
                                        <label for="nationality">Nationality:</label>
                                        <input type="text" id="nationality" name="nationality" value="{{$employee->nationality}}"  required/>
                                    </p>
                                    <p>
                                        <label for="phoneNbr">Phone Number:</label>
                                        <input type="text" id="phoneNbr" name="phoneNbr" value="{{$employee->phone_nbr}}" required/>
                                    </p>
                                    <p>
                                        <label for="birthDate">Birth Date:</label>
                                        <input type="date" id="birthDate" name="birthDate" max="<?php echo date('Y-m-d', strtotime('-17 years')); ?>" value="{{$employee->birth_date}}" required/>
                                    </p>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Save Changes"/>
                        </form>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Address Info
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="post" action="{{ route('employees.edit.address', ['eID' => $employee->employee_profile_id, 'aID' => $employee->address_id, 'uID' => $employee->user_id]) }}">
                            @csrf
                            <div class="row">
                                <div class="col-3">
                                    <p>
                                        <label for="street">Street:</label>
                                        <input type="text" id="street" name="street" value="{{$employee->street}}" required/>
                                    </p>
                                    <p>
                                        <label for="number">Number:</label>
                                        <input type="number" id="number" name="number" min="1" value="{{$employee->number}}" required/>
                                    </p>
                                    <p>
                                        <label for="box">Box:</label>
                                        <input type="text" id="box" name="box" value="{{$employee->box}}" required/>
                                    </p>
                                </div>

                                <div class="col-3">
                                    <p>
                                        <label for="city">City:</label>
                                        <input type="text" id="city" name="city" value="{{$employee->city}}" required/>
                                    </p>
                                    <p>
                                    <label for="province">Province:</label>
                                    <select name="province" id="province">
                                        <option value="Brussels" @if($employee->province == 'Brussels') selected @endif>Brussels</option>
                                        <option value="Flemish Brabant" @if($employee->province == 'Flemish Brabant') selected @endif>Flemish Brabant</option>
                                        <option value="Antwerp" @if($employee->province == 'Antwerp') selected @endif>Antwerp</option>
                                        <option value="Limburg" @if($employee->province == 'Limburg') selected @endif>Limburg</option>
                                        <option value="Liège" @if($employee->province == 'Liège') selected @endif>Liège</option>
                                        <option value="Namur" @if($employee->province == 'Namur') selected @endif>Namur</option>
                                        <option value="Hainaut" @if($employee->province == 'Hainaut') selected @endif>Hainaut</option>
                                        <option value="Luxembourg" @if($employee->province == 'Luxembourg') selected @endif>Luxembourg</option>
                                        <option value="West Flanders" @if($employee->province == 'West Flanders') selected @endif>West Flanders</option>
                                        <option value="East Flanders" @if($employee->province == 'East Flanders') selected @endif>East Flanders</option>
                                    </select>
                                    </p>
                                    <p>
                                        <label for="postalCode">Postal Code:</label>
                                        <input type="number" id="postalCode" name="postalCode" min="1000" max="9992" value="{{$employee->postal_code}}" required/>
                                    </p>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Save Changes"/>
                        </form>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Contract Info
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="post" action="{{ route('employees.edit.contract', ['eID' => $employee->employee_profile_id, 'uID' => $employee->user_id]) }}">
                            @csrf
                            <div class="row">
                                <div class="col-3">
                                    <p>
                                        <label for="contractType">Contract Type:</label>
                                        <select name="contractType" id="contractType">
                                            <option value="Full-time" @if($employee->type == 'Full-time') selected @endif>Full-time</option> 
                                            <option value="Part-time" @if($employee->type == 'Part-time') selected @endif>Part-time</option> 
                                            <option value="Internship" @if($employee->type == 'Internship') selected @endif>Internship</option> 
                                        </select>
                                    </p>
                                    <p>
                                        <label for="startDate">Start Date:</label>
                                        <input type="date" id="startDate" name="startDate" min="<?php echo date('Y-m-d'); ?>" value="{{$employee->start_date}}" readonly/>
                                    </p>
                                    <p>
                                        <label for="endDate">End Date:</label>
                                        <input type="date" id="endDate" name="endDate" value="{{$employee->end_date}}"/>
                                    </p>
                                </div>
                                
                                <div class="col-3">
                                    <p>
                                        <label for="salary">Salary per month:</label>
                                        <input type="number" id="salary" name="salary" min="1700" value="{{$employee->salary_per_month}}" required/>
                                    </p>
                                    <p>
                                        <label for="team">Team:</label>
                                        <select name="team" id="team">
                                            @foreach($teams as $team)
                                                <option value="{{ $team->team_name }}" @if($employee->team_name == $team->team_name) selected @endif>{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                    <p>
                                        <label for="role">Role:</label>
                                        <select name="role" id="role">
                                            <!-- if manager selected, select what department managing -->
                                            <!-- Depending on selected team -->
                                            @foreach($roles as $role)
                                                <option value="{{ $role->role_name }}" @if($employee->role_name == $role->role_name) selected @endif>{{ $role->role_name }}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Save Changes"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form method="post">

            <button type="button" class="btn btn-primary"><a href="{{ route('employees') }}">< Back</a></button>
        </form>
    </body>
</html>