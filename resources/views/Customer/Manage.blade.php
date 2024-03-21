<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage</title>
    <link href="/css/Joren/reset.css" rel="stylesheet"/>
    <link href="/css/Joren/main.css" rel="stylesheet"/>
    <link href="/css/Joren/manage.css" rel="stylesheet"/>

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
<!-- TODO: daadwerkelijke data weergeven. -->
        <div class="box">
            <h1 class="boxHeader" id="test">Your personal info</h1>
            <!-- Password -->
            <table class="boxTable" id="box1_table_1">
                
                <tr>
                    <td>
                        <p>Calling</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>MR</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>First name</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{$user->first_name}}</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>Last name</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>Testing</p>
                    </td>
                </tr>

                
            </table>

            <table class="boxTable" id="box1_table_2">
                <tr>
                    <td>
                        <p>Email</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>test@tester.com</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>Phone number</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>0032 458 32 56 89</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>Company name</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>test Company</p>
                    </td>
                </tr>

            </table>

            <form method='post' action="{{ route('ChangeUser') }}">
                @csrf
                <input type="submit" value="change" class="change" id="changePerson">
            </form>
            

        </div>

        <!-- TODO: een 2de adres toe voegen of meerdere addressen. -->
        <div class="box">
            <h1 class="boxHeader">Your house info</h1>
            <table class="boxTable" id="box1_table_1">
                <tr>
                    <td>
                        <p>region</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>Antwerpen</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>City</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>Sint-Katelijne-Waver</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>postalCode</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>2860</p>
                    </td>
                </tr>
            </table>

            <table class="boxTable" id="box1_table_2">
                <tr>
                    <td>
                        <p>street</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>Jan Pieter de Nayerlaan</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>number</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>5</p>
                    </td>
                </tr>

                <!-- Bus moet nog in de in de database kunnen geplaatst worden -->
                <tr>
                    <td>
                        <p>bus</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>A</p>
                    </td>
                </tr>
            </table>

            <p>Je kan mss een change Request doen. dat je het kan aan passen maar niet direct wordt aangepast. tot het is goed gekeurd is.</p>
            <form>
                <input type="button" value="change" class="change">
            </form>
        </div>

    </div>
</body>
</html>
