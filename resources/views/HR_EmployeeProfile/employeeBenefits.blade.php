<x-app-layout>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="/css/jobs.css" rel="stylesheet" type="text/css"/>
        <link href="/css/nav.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Job offers</title>

    </head>

    @php 
        $role = DB::select("select *from roles inner join user_roles on user_roles.role_id = roles.id where user_roles.user_id = " . Auth::id());
        $benefits = DB::select("select * from employee_benefits where role_id = " . $role[0]->role_id);
    @endphp

    <body>
            <div class="top-page">
                <img src="/images/Employee_Benefits.jpg" alt="green energy" style="height: 200px"/>
            </div>
            <div class="container">
                <h2 class="mb-3 text-dark" style="text-align: center; margin-top: 40px; margin-bottom: 30px">As {{$role[0]->role_name == 'Employee' ? "an " . $role[0]->role_name : "a " . $role[0]->role_name}} in our company, you get the following benefits</h2><br>

                {{-- <div class="container-trui"> --}}
                    <div class="container" style="background-color: white; padding: 20px">
                        @foreach($benefits as $benefit)
                        <h5 class="card-title">{{$benefit->benefit_name}}</h5>
                        <p class="card-text">{{$benefit->description}} </p>
                    @endforeach
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </body>
    </html>
</x-app-layout>