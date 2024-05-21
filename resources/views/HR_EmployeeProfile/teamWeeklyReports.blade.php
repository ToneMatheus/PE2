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
          <title>Weekly Report</title>
          <style>
              .hover-effect {
                  transition: transform 0.3s, box-shadow 0.3s;
              }
              .hover-effect:hover {
                  transform: scale(1.05);
                  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
              }
          </style>
      </head>

      <body>
        @if($weeks != null)
        @foreach($weeks as $week)
        @php
            // Split the week range into start and end dates
            list($startOfWeek, $endOfWeek) = explode(' - ', $week);
        @endphp
    
        <h2 style="margin-top: 40px; text-align: center; display: block; background-color: white;">Week of {{$week}}</h2>
        
        <div class="container" style="display: flex; justify-content: space-between; margin-top: 30px;">
        @foreach($weekly_reports as $reps) 
            @php
                // Convert submission date to Carbon instance for comparison
                $submissionDate = \Carbon\Carbon::parse($reps->submission_date);
                $startOfWeekDate = \Carbon\Carbon::parse($startOfWeek);
                $endOfWeekDate = \Carbon\Carbon::parse($endOfWeek);
            @endphp
    
            @if($submissionDate->between($startOfWeekDate, $endOfWeekDate))
            @php
              $employee = DB::select("select * from users where employee_profile_id = $reps->employee_profile_id");
              $employee_name = $employee[0]->first_name . " " . $employee[0]->last_name;
              $emp_id = $employee[0]->employee_profile_id;
            @endphp

            <a href="{{ route('individualReports', ['id' => $emp_id]) }}">
              <div class="hover-effect p-4 bg-white rounded-lg shadow-md">
                <img src="/images/report.png" style="width: 150px; display: block; margin: 0 auto 10px;" /> 
                <span style="display: block; text-align: center;">Author: {{$employee_name}}</span>
              </div>
            </a>
            @endif
        @endforeach
      </div><br/>
    @endforeach
    @else
      <div><h2 style="text-align: center">Reports currently unavailable</h2></div>
    @endif
      </body>
  </html>
</x-app-layout>