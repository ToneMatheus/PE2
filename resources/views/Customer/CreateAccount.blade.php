<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account</title>
    <link href="/css/Joren/reset.css" rel="stylesheet"/>
    <link href="/css/Joren/main.css" rel="stylesheet"/>
    <link href="/css/Joren/CreateAccount.css" rel="stylesheet"/>

    <script>
        window.onload = function(){
            checkbox();
        }

        function shpsswd($psswd) {
            var x = document.getElementById($psswd);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function checkbox(){
            var checkbox = document.getElementById("Company");
            var show = document.getElementsByClassName("CompanyField");
            var typeHouseSelect = document.getElementsByName("typeHouse")[0];
            var businessOption = typeHouseSelect.querySelector('option[value="business"]');

            if(checkbox.checked == true){
                for (var i = 0; i < show.length; i++) {
                    show[i].style.visibility = "visible";
                }
                typeHouseSelect.value = "business";
                businessOption.disabled = false;
                businessOption.style.display = "block";  
            }else{
                for (var j = 0; j < show.length; j++) {
                    show[j].style.visibility = "hidden";
                }
                typeHouseSelect.value = "house";
                businessOption.disabled = true;
                businessOption.style.display = "none";               
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
            <h1 class="boxHeader">Create account</h1>
            <form method='post' action="{{ route ('postCreateAccountValidate') }}">
                <table>
                    <tr>
                        <td>
                            <label>Name</label>
                        </td>
                        <td>
                            <input type="text" placeholder="First name" name="FirstName" value="{{old('FirstName')}}" required/>

                        </td>
                        
                        <td>
                            <label>Surname</label>
                        </td>
                        <td>
                            <input type="text" placeholder="Last name" name="LastName" value="{{old('LastName')}}" required/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Email</label>
                        </td>
                        <td>
                            <input type="email" placeholder="Email" name="Email" value="{{old('Email')}}" required/>
                        </td>

                        <td>
                            <label>Username</label>
                        </td>
                        <td>
                            <input type="text" placeholder="username" name="Username" value="{{old('Username')}}" required/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Calling</label>
                        </td>
                        <td>
                            <select name="Calling">
                                <option value="MR" @if(old('Calling') == 'MR') selected @endif>MR</option>
                                <option value="MS"  @if(old('Calling') == 'MS') selected @endif>MS</option>
                            </select>
                        </td>

                        <td>
                            <label>Phone number (optional)</label>
                        </td>
                        <td>
                            <input type="tel" placeholder=" 0123 453 210" pattern="0[0-9]{3} [0-9]{3} [0-9]{3}" name="PhoneNummer"
                             value="{{old('PhoneNummer')}}"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Birthdate</label>
                        </td>
                        <td>
                            <input type="date" id="birthday" name="birthday" value="{{old('birthday')}}">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Password</label>
                        </td>
                        <td>  
                            <input type="password" placeholder="Password" id="paswd1" name="PaswdNew1" value="{{old('PaswdNew1')}}" />
                            <input type="checkbox" onclick="shpsswd('paswd1')">
                            <label>show</label>
                        </td>

                        <td>
                            <label>Password comfirm</label>
                        </td>
                        <td>
                            <input type="password" placeholder="Password" id="paswd2" name="PaswdNew2" value="{{old('PaswdNew2')}}" />
                            <input type="checkbox" onclick="shpsswd('paswd2')">
                            <label>show</label>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>For company</label>
                        </td>
                        <td>
                            <input type="checkbox" id="Company" name="isCompany" onclick="checkbox()" @if(old('isCompany')) checked @endif/>
                        </td>

                        <td class="CompanyField">
                            <label>Company name</label>
                        </td>
                        <td class="CompanyField">
                            <input type="text" placeholder="Company name" name="CompanyName" value="{{old('CompanyName')}}"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Street</label>
                        </td>
                        <td>
                            <input type="text" placeholder="Street name" name="Street" value="{{old('Street')}}" required/>
                        </td>
                        
                        <td>
                            <label>Number</label>
                        </td>
                        <td>
                            <input type="text" placeholder="10" name="Number" value="{{old('Number')}}" required/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Province</label>
                        </td>
                        <td>
                            <input type="text" placeholder="Province" name="Province" value="{{old('Province')}}" required/>
                        </td>

                        <td>
<!-- //? zien of het optioneel moet zijn. -->
                            <label>Box</label>
                        </td>
                        <td>
                            <input type="text" placeholder="A" name="Bus" value="{{old('Bus')}}"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>PostalCode</label>
                        </td>
                        <td>
                            <input type="text" placeholder="4000" name="PostalCode" value="{{old('PostalCode')}}" required/>
                        </td>

                        <td>
                            <label>City</label>
                        </td>
                        <td>
                            <input type="text" placeholder="City" name="City" value="{{old('City')}}" required/>
                        </td>    
                    </tr>

                    <tr>
                        <td>
                            <label>type of house</label>
                        </td>
                        <td>
                        <select name="typeHouse">
                                <option value="house" @if(old('typeHouse') == 'house') selected @endif>House</option>
                                <option value="appartment"  @if(old('typeHouse') == 'appartment') selected @endif>Appartment</option>
                                <option value="business"  @if(old('typeHouse') == 'business') selected @endif>Business</option>
                            </select>
                        </td>
                    </tr>

                </table>
                @csrf
                <input type="submit" value="Create" id="createAccount">
            </form>
        </div>

        //LOOK: je krijgt een beveseging email.
            

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