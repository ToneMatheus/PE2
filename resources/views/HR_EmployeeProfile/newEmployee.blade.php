<!DOCTYPE html>
<html>
    <head>
        <title>New Employee</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/newEmployee.css') }}"/>
    </head>
    <body>
        <h1>New Employee</h1>
        
        <!-- Username generated like schoolID, email based of generated email & password default that they have to change (mail) -->
        <form method="post" action="{{ route('newEmployee.add') }}">
            @csrf
            <h2>Personal info:</h2>
            <div class="row">
                <div class="col-3">
                    <p>
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName"/>
                    </p>
                    <p>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name"/>
                    </p>
                    <p>
                        <label for="title">Honorific:</label>
                        <select name="title" id="title">
                            <option value="Mr.">Mr.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Mx.">Mx.</option>
                        </select>
                    </p>
                </div>

                <div class="col-3">
                    <p>
                        <label for="nationality">Nationality:</label>
                        <input type="text" id="nationality" name="nationality"/>
                    </p>
                    <p>
                        <label for="phoneNbr">Phone Number:</label>
                        <input type="text" id="phoneNbr" name="phoneNbr"/>
                    </p>
                    <p>
                        <label for="birthDate">Birth Date:</label>
                        <input type="date" id="birthDate" name="birthDate"/>
                    </p>
                </div>
            </div>

            <h2>Address info:</h2>
            <div class="row">
                <div class="col-3">
                    <p>
                        <label for="street">Street:</label>
                        <input type="text" id="street" name="street"/>
                    </p>
                    <p>
                        <label for="number">Number:</label>
                        <input type="number" id="number" name="number"/>
                    </p>
                    <p>
                        <label for="box">Box:</label>
                        <input type="text" id="box" name="box"/>
                    </p>
                </div>

                <div class="col-3">
                    <p>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city"/>
                    </p>
                    <p>
                        <label for="province">Province:</label>
                        <input type="text" id="province" name="province"/>
                    </p>
                    <p>
                        <label for="postalCode">Postal Code:</label>
                        <input type="number" id="postalCode" name="postalCode"/>
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
                        <input type="date" id="startDate" name="startDate"/>
                    </p>
                    <p>
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="endDate"/>
                    </p>
                </div>
                
                <div class="col-3">
                    <p>
                        <label for="salary">Salary per month:</label>
                        <input type="number" id="salary" name="salary"/>
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
                            @foreach($roles as $role)
                                <option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </p>
                </div>
            </div>

            <input type="submit" class="btn btn-primary"/>
        </form>
    </body>
</html>