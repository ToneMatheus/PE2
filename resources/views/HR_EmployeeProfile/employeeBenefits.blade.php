<x-app-layout>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="/css/jobs.css" rel="stylesheet" type="text/css"/>
        <link href="/css/nav.css" rel="stylesheet"/>
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> --}}
        <title>Job offers</title>

         <style>
            /* :root {
                --bg-color: white;
                --text-color: black;
                --card-bg-color: #f8f9fa;
                --hover-bg-color: #e9ecef;
                --border-color: #dee2e6;
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --bg-color: #121212;
                    --text-color: white;
                    --card-bg-color: #1e1e1e;
                    --hover-bg-color: #333;
                    --border-color: #444;
                }
            }

            body {
                background-color: var(--bg-color);
                color: var(--text-color);
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 20px;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
            }

            .header {
                text-align: center;
                margin-bottom: 50px;
            }

            .header h1 {
                font-size: 3em;
                margin: 0;
            }

            .card {
                border: 1px solid var(--border-color);
                border-radius: 10px;
                background-color: var(--card-bg-color);
                overflow: hidden;
                margin-bottom: 30px;
                transition: background-color 0.3s ease;
            }

            .card:hover {
                background-color: var(--hover-bg-color);
            }

            .card-body {
                padding: 20px;
                display: flex;
                align-items: center;
            }

            .card-body img {
                width: 80px;
                height: 80px;
                margin-right: 20px;
            }

            .card-body h5 {
                margin: 0;
                font-size: 1.5em;
            }

            .card-body p {
                margin: 5px 0 0;
                font-size: 1em;
                color: var(--text-color);
            } */
        </style>
    </head>

    @php 
        $role = DB::select("select * from roles inner join user_roles on user_roles.role_id = roles.id where user_roles.user_id = " . Auth::id());
        $roleId = DB::select("select role_id from user_roles where user_id = ".  Auth::id());
        $roleId = $roleId[0]->role_id;
        $user = DB::select("select * from users where id = " . Auth::id());

        $profile = DB::select("select * from employee_profiles where id = " . $user[0]->employee_profile_id);

        if($profile[0]->score <= 10){
            $benefits = DB::select("select * from employee_benefits where premium = '' and role_id = " . $roleId);
            $salary = DB::select("select min_salary from salary_ranges where role_id = " . $roleId);
            $salary = $salary[0]->min_salary;
            $status = "pending";
        }
        else{
            $benefits = DB::select("select * from employee_benefits where role_id = " . $roleId);
            $salary = DB::select("select max_salary from salary_ranges where role_id = " . $roleId);
            $salary = $salary[0]->max_salary;
            $status = "promoted";
        }
    @endphp

    <body>
            <div class="top-page">
                <img src="/images/Employee_Benefits.jpg" alt="green energy" style="height: 200px"/>
            </div>
            <div class="container">
                <h2 class="mb-3 text-white" style="text-align: center; margin-top: 40px; margin-bottom: 30px; color: white; font-size: 30px">As {{$role[0]->role_name == 'Employee' ? "an " . $role[0]->role_name : "a " . $role[0]->role_name}} in our company, you get the following benefits</h2><br>

                {{-- <div class="container-trui"> --}}
                    <div class="container" style="padding: 10px; width: 70%; margin:auto;">
                        @foreach($benefits as $benefit)
                        <div style="border-radius: 20px; margin-bottom: 20px; background-color: white; padding: 20px;">
                            <div style="float: left; display: flex; align-items: center">
                                <img src="{{$benefit->image}}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div style="margin-left: 70px">
                                <h5 class="card-title" style="margin-right: 10px">{{$benefit->benefit_name}}</h5>
                                <p class="card-text">{{$benefit->description}} </p>
                            </div>
                        </div>
                    @endforeach
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </body>
    </html>
</x-app-layout>