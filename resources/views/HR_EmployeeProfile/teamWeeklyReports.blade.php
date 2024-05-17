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

        <h2 style="margin-top: 20px; text-align: center;">Week 1</h2>

        <body>
            <table class="table table-bordered" style="background-color: white; margin-top: 20px;">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Author</th>
                    <th scope="col">Summary</th>
                    <th scope="col">Tasks completed</th>
                    <th scope="col">Upcoming tasks</th>
                    <th scope="col">Challenges faced</th>
                    <th scope="col">Submission date</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($weekly_reports as $reps) 
                  <tr>
                    <th scope="row">1</th>
                    <td>Bob</td>
                    <td>{{$reps->summary}}</td>
                    <td>{{$reps->tasks_completed}}</td>
                    <td>{{$reps->upcoming_tasks}}</td>
                    <td>{{$reps->challenges}}</td>
                    <td>{{$reps->submission_date}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

        </body>
    </html>
</x-app-layout>