<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage</title>
    <link href="/css/Joren/reset.css" rel="stylesheet"/>
    <link href="/css/Joren/main.css" rel="stylesheet"/>
    <link href="/css/Joren/manageChangeUser.css" rel="stylesheet"/>

    <script>
        function shpsswd($psswd) {
        var x = document.getElementById($psswd);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <header> 
            <p class="CompanyNameH1">Name Company</p>
        </header>

        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href=#>Tariffs</a></li>
                <li><a href=#>Consumption Calculator</a></li>
                <li><a href=#>Support</a></li>
                <li><a href="{{ route('Manage') }}" class="active">Manage</a></li>
                <li><a href="{{ route('createUser') }}">Create account</a></li>
            </ul>
        </nav>

        <div class="box">
            <h1>Profile</h1>
                <form method='post'  action="{{ route ('postProfile') }}">
                    <div class="input-container">
                        <div class ="input1">
                            <label id="FirstName">First Name *</label> 
                            <input type="text" value="testname" placeholder="First name" name="FirstName"/>
                        </div>

                        <div class ="input2">
                            <label id="LastName">Last Name *</label> 
                            <input type="text" value="Testing" placeholder="Last name" name="LastName"/>
                        </div>
                    </div>

                    <div class="input-container">
                        <div class ="input1">
                            <label id="Calling">Calling *</label>
                            <select name="Calling">
                                <option value="MR">MR</option>
                                <option value="MS">MS</option>
                            </select>
                        </div>

                        <div class ="input2">
                            <label id="PhoneNumber">Phone number</label>
                            <input type="tel" value="0032 458 32 56 89" placeholder="0032 123 45 32 10"
                            pattern="[0]{2}32 [0-9]{3} [0-9]{2} [0-9]{2} [0-9]{2}"/>
                        </div>
                    </div>

                    <div class="button-container">
                        @csrf
                        <input type="submit" value="Save change" id="profile">
                    </div>
                </form>
        </div>

        <div class="box">
            <h1>Email addres</h1>
            <form method='post' action="{{ route ('postEmail') }}">
                <div class="input-container">
                    <div class ="input1">
                        <label id="LastName">Email *</label> 
                        <input type="email" value="test@tester.com" placeholder="Email" name="email1"/>
                    </div>

                    <div class ="input2">
                        <label id="LastName">comfirm email *</label> 
                        <input type="email" placeholder="Email" name="email2"/>
                    </div>
                </div>

                <div class="button-container">
                    @csrf
                    <input type="submit" value="Save change" id="email">
                </div>
            </form>
        </div>

        <div class="box">
            <h1>password</h1>
            <form method='post' action="{{ route ('postPasswd') }}">
                <div class="input-container">
                    <div class ="input1">
                        <label id="LastName">Old *</label> 
                        <input type="password" value="test" placeholder="Password" id="paswd1" name="paswdOld"/>
                        <input type="checkbox" onclick="shpsswd('paswd1')">
                        <label>show</label>
                    </div>
                </div>

                <div class="input-container">
                    <div class ="input1">
                        <label id="LastName">New *</label> 
                        <input type="password" placeholder="Password" id="paswd2" name="paswdNew1"/>
                        <input type="checkbox" onclick="shpsswd('paswd2')">
                        <label>show</label>
                    </div>

                    <div class ="input2">
                        <label id="LastName">New *</label> 
                        <input type="password" placeholder="Password" id="paswd3" name="paswdNew2"/>
                        <input type="checkbox" onclick="shpsswd('paswd3')">
                        <label>show</label>
                    </div>
                </div>

                <div class="button-container">
                    @csrf
                    <input type="submit" value="Save change" id="psswd">
                </div>
            </form>
            <p>de lengte en de regex moet ik nog testen. en de boodschappen.</p>
        </div> 
    </div>
</body>
</html>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
