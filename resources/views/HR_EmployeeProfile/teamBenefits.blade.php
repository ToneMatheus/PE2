<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            {{-- <link href="/css/payList.css" rel="stylesheet" type="text/css"/>
            <link href="/css/header.css" rel="stylesheet" type="text/css"/> --}}
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <title>Team benefits</title>
        </head>

        @php 
            $role = DB::select("select *from roles inner join user_roles on user_roles.role_id = roles.id where user_roles.user_id = " . Auth::id());
            $benefits = DB::select("select * from employee_benefits where role_id = 2");
            $i=0
        @endphp

        <body>
            <div style="padding: 20px; color: white">
                <h1 style="text-align: center">Employee benefits page</h1><br/>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Each of your employees at the start of their contracts, have a base salary of about 4000$. This already mentioned salary does also have an effect on the benefits earned by the employee in the company. However, the company constantly <a href="#">evaluates</a> employees and depending on their performance in a defined duration, they can be qualified to get a raise and also enjoy more benefits than the average employee. <a href="#myEmployees">Review my employees</a></p> 
            
            <p>Below are all the benefits per normal employee with the extra benefits of promoted employees in red.</p>
            <div class="container">
                <table class="table table-bordered" style="background-color: white">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Premium benefits</th>
                        <th scope="col">Normal benefits</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($benefits as $b)
                            <tr>
                                <th scope="row"><img src="{{$b->image}}" style="width: 30px; height: 30px"/></th>
                                @if($b->premium == '')
                                    <td>{{$b->benefit_name}}</td>
                                @endif
                                @if($b->premium == 'yes')
                                    <td style="color: red">{{$b->benefit_name}}</td>
                                @else
                                    <td>{{$b->benefit_name}}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>

            <br/><h2 id="myEmployees" style="text-align: center">My employees</h2><br/>

            <div class="container">
                <table class="table table-bordered" style="background-color: white">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full name</th>
                        <th scope="col">Current score</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Benefits</th>
                        <th scope="col">Status</th>
                        <th scope="col" colspan="2">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($team_members as $tm)
                    @php
                        $profile = DB::select("select * from employee_profiles where id = " . $tm->employee_profile_id);

                        // $role = DB::select("select * from roles inner join user_roles on user_roles.role_id = roles.id where user_roles.user_id = " . $tm->id);
                        $roleId = 2;//role id for employees
                        
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
                        @if($status == "promoted")
                            <tr class="table-success">
                                <th scope="row">{{$tm->employee_profile_id}}</th>
                                <td>{{$tm->first_name}} {{$tm->last_name}}</td>
                                <td>{{$profile[0]->score}}</td>
                                <td>{{$salary}}$</td>
                                <td>
                                    <div style="display: flex; justify-content: space-around">
                                        @foreach($benefits as $b)
                                            <img src="{{$b->image}}" style="width: 25px"/>
                                        @endforeach
                                    </div>
                                </td>
                                <td><span>{{$status}}</span></td>
                                <td><a href="{{route('evaluations')}}"><button class="btn btn-primary">View evaluation</button></a></td>
                            </tr>
                        @else
                            <tr>
                                <th scope="row">{{$tm->employee_profile_id}}</th>
                                <td>{{$tm->first_name}} {{$tm->last_name}}</td>
                                <td>{{$profile[0]->score}}</td>
                                <td>{{$salary}}$</td>
                                <td>
                                    <div style="display: flex; justify-content: space-around">
                                        @foreach($benefits as $b)
                                            <img src="{{$b->image}}" style="width: 25px"/>
                                        @endforeach
                                    </div>
                                </td>
                                <td><span>{{$status}}</span></td>
                                <td><a href="{{route('evaluations')}}"><button class="btn btn-primary">View evaluation</button></a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                  </table>
            </div>
            </div>
        </body>
    </html>
</x-app-layout>