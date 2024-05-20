<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Financial analyst</title>

    </head>

    <body>
        <table class="table table-striped" style="width: 80%; margin: auto;">
        <thead>
            <tr>
                <th scope="col">Emp id</th>
                <th scope="col">First name</th>
                <th scope="col">Last name</th>
                <th scope="col">Job title</th>
                <th scope="col">Hours worked</th>
                <th scope="col">Generated revenue</th>
                <th scope="col">Monthly pay</th>
                <th scope="col">Min salary</th>
                <th scope="col">Max salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>{{$roleNames[$i]->role_name}}</td>
                    <td>/</td>
                    <td>/</td>
                    <td>/</td>
                    <td>{{$salaries[$i]->min_salary}}$</td>
                    <td>{{$salaries[$i]->max_salary}}$</td>
                </tr>
                {{$i++;}}
            @endforeach
        </tbody>
    </table>

    </body>
</x-app-layout>