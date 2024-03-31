<!DOCTYPE html>
<html>
    <head>
        <title>New Employee</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="{{ asset('css/employeeOverview.css') }}"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function newEmployee(toggle){
                if(toggle){
                    document.getElementById('newEmployee').style.display = 'block';
                    document.getElementById('newEmployeeBttn').style.display = 'none';
                } else {
                    document.getElementById('newEmployee').style.display = 'none';
                    document.getElementById('newEmployeeBttn').style.display = 'block';
                }
            }
        </script>
    </head>
    <body>
        <h1>Employee Overview</h1>

        <div class="container">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="col-1">E_ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Name</th>
                        <th scope="col">Team</th>
                        <th scope="col" class="col-1">Edit</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <th scope="row">{{$employee->employee_profile_id}}</th>
                            <td>{{$employee->first_name}}</td>
                            <td>{{$employee->last_name}}</td>
                            <td>{{$employee->team_name}}</td>
                            <td><a href="{{ route('employees.edit', ['eID' => $employee->employee_profile_id]) }}"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div>
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <button class="btn btn-primary" onclick="newEmployee(1)" id="newEmployeeBttn"><i class="bi bi-person-plus-fill"></i></button>
        
        <!-- Username generated like schoolID, email based of generated email & password default that they have to change (mail) -->
        <form method="post" action="{{ route('employees.add') }}" id="newEmployee">
            @csrf
            <h2>Personal info:</h2>
            <div class="row">
                <div class="col-3">
                    <p>
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" required/>
                    </p>
                    <p>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required/>
                    </p>
                    <p>
                        <label for="title">Honorific:</label>
                        <select name="title" id="title">
                            <option value="Mr">Mr.</option>
                            <option value="Ms">Ms.</option>
                            <option value="X">Mx.</option>
                        </select>
                    </p>
                    <p>
                        <label for="nationality">Nationality:</label>
                        <input type="text" id="nationality" name="nationality" required/>
                    </p>
                </div>

                <div class="col-3">
                    <p>
                        <label for="personEmail">Email:</label>
                        <input type="email" id="personEmail" name="personalEmail" required/>
                    </p>
                    <p>
                        <label for="phoneNbr">Phone Number:</label>
                        <input type="text" id="phoneNbr" name="phoneNbr" required/>
                    </p>
                    <p>
                        <label for="birthDate">Birth Date:</label>
                        <input type="date" id="birthDate" name="birthDate" max="<?php echo date('Y-m-d', strtotime('-17 years')); ?>" required/>
                    </p>
                </div>
            </div>

            <h2>Address info:</h2>
            <div class="row">
                <div class="col-3">
                    <p>
                        <label for="street">Street:</label>
                        <input type="text" id="street" name="street" required/>
                    </p>
                    <p>
                        <label for="number">Number:</label>
                        <input type="number" id="number" name="number" min="1" required/>
                    </p>
                    <p>
                        <label for="box">Box:</label>
                        <input type="text" id="box" name="box" required/>
                    </p>
                </div>

                <div class="col-3">
                    <p>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" required/>
                    </p>
                    <p>
                    <label for="province">Province:</label>
                    <select name="province" id="province">
                        <option value="Brussels">Brussels</option>
                        <option value="Flemish Brabant">Flemish Brabant</option>
                        <option value="Antwerp">Antwerp</option>
                        <option value="Limburg">Limburg</option>
                        <option value="Liège">Liège</option>
                        <option value="Namur">Namur</option>
                        <option value="Hainaut">Hainaut</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="West Flanders">West Flanders</option>
                        <option value="East Flanders">East Flanders</option>
                    </select>
                    </p>
                    <p>
                        <label for="postalCode">Postal Code:</label>
                        <input type="number" id="postalCode" name="postalCode" min="1000" max="9992" required/>
                    </p>
                </div>
            </div>

            <h2>Contract info:</h2>
            <div class="row">
                <div class="col-3">
                    <p>
                        <label for="contractType">Contract Type:</label>
                        <select name="contractType" id="contractType">
                            <option value="Full-time">Full-time</option> 
                            <option value="Part-time">Part-time</option> 
                            <option value="Internship">Internship</option> 
                        </select>
                    </p>
                    <p>
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="startDate" min="<?php echo date('Y-m-d'); ?>" required/>
                    </p>
                    <p>
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="endDate" required/>
                    </p>
                </div>
                
                <div class="col-3">
                    <p>
                        <label for="salary">Salary per month:</label>
                        <input type="number" id="salary" name="salary" min="1700" required/>
                    </p>
                    <p>
                        <label for="team">Team:</label>
                        <select name="team" id="team">
                            @foreach($teams as $team)
                                <option value="{{ $team->team_name }}">{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                    </p>
                    <p>
                        <label for="role">Role:</label>
                        <select name="role" id="role">
                            <!-- if manager selected, select what department managing -->
                            <!-- Depending on selected team -->
                            @foreach($roles as $role)
                                <option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </p>
                </div>
            </div>

            <input type="submit" class="btn btn-primary"/>
            <button type="button" onclick="newEmployee(0)" class="btn btn-danger">Cancel</button>
        </form>
    </body>
</html>