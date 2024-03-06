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
        function shpsswd($psswd) {
            var x = document.getElementById($psswd);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function Company(){
            var compinput = document.getElementById("CompanyField");
            var compbtn = document.getElementById("Company");
            
            if(compbtn.checked){
                compinput.style.visibility = "visible";
            }
            else{
                compinput.style.visibility = "hidden";
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
            <h1 class="boxHeader" id="test">Create account</h1>
            <table>
                <tr>
                    <td>
                        <label>name</label>
                    </td>
                    <td>
                        <input type="text" placeholder="First name" name="FirstName"/>
                    </td>
                    
                    <td>
                        <label>surname</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Last name" name="LastName"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>email</label>
                    </td>
                    <td>
                        <input type="email" placeholder="Email" name="email1"/>
                    </td>

                    <td>
                        <label>Phone number (optional)</label>
                    </td>
                    <td>
                        <input type="tel" placeholder="0032 123 45 32 10" pattern="[0]{2}32 [0-9]{3} [0-9]{2} [0-9]{2} [0-9]{2}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Password</label>
                    </td>
                    <td>  
                        <input type="password" placeholder="Password" id="paswd1" name="paswdNew1"/>
                        <input type="checkbox" onclick="shpsswd('paswd1')">
                        <label>show</label>
                    </td>

                    <td>
                        <label>Password comfirm</label>
                    </td>
                    <td>
                        <input type="password" placeholder="Password" id="paswd2" name="paswdNew2"/>
                        <input type="checkbox" onclick="shpsswd('paswd2')">
                        <label>show</label>
                    </td>
                </tr>

                <tr>
                    <td>
                    <label>For company (optional)</label>
                    </td>
                    <td>
                    <input type="checkbox" onclick="Company()" id="Company">
                    </td>

                    <div id="CompanyField">
                        <td>
                            <label>Company name</label>
                        </td>
                        <td>
                            <input type="text" placeholder="Company name" name="CompanyName"/>
                        </td>
                    </div>
                </tr>

            </table>
            @csrf
            <input type="submit" value="Create" id="createAccount">
        </div>

        <h1>eerst vraag je de voornaam en achternaam.</h1>
        <h1>dan vraag je email.</h1>
        <h1>het telefoon nummer dat optioneel is.</h1>
        <h1>passwoord 2 keer</h1>
        <h1>een check box of het voor een bedrijf is. wat optioneel is</h1>
        <h1>zo ja toon een extra veld met de vraag voor welk bedrijf.</h1>
        <p>eventueel username</p>
        <p>je krijgt een bevesteging email.</p>
            

    </div>
</body>
</html>
