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
            <title>Weekly reports for:</title>
        </head>

        <body class="bg-gray-100">
            <div class="container mx-auto p-8">
                <!-- Employee Information Header -->
                <div class="mb-8 p-6 bg-blue-100 rounded-lg shadow-lg text-center">
                    <h1 class="text-3xl font-bold text-blue-800">Weekly Reports for {{$employee[0]->first_name}} {{$employee[0]->last_name}}</h1>
                    <p class="text-blue-700">Employee ID: {{$employee[0]->employee_profile_id}}</p>
                </div>

                <style>
                    h5{
                        font-weight: bold;
                    }
                </style>
        
                <!-- Reports Section -->
                <div class="space-y-6">
                    @foreach($reports as $report)
                        <div class="p-6 bg-white rounded-lg shadow-md">
                            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Report</h2>
                            <p class="mb-2"><h5>1. Summary:</h5> {{ $report->summary }}</p>
                            <p class="mb-2"><h5>2. Tasks Completed:</h5> {{ $report->tasks_completed }}</p>
                            <p class="mb-2"><h5>3. Upcoming Tasks:</h5> {{ $report->upcoming_tasks }}</p>
                            <p class="mb-2"><h5>4. Challenges:</h5> {{ $report->challenges }}</p>
                            <p class="text-gray-600"><h5>5. Submission Date:</h5> {{ \Carbon\Carbon::parse($report->submission_date)->format('F j, Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </body>
    </html>
</x-app-layout>