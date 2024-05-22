{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="/css/dashboard.css" rel="stylesheet" type="text/css"/>

    <!-- The callback parameter is required, so we use console.debug as a noop -->
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByBdD-HWq4mvd5hh2A_4HsIV3kBpp2HiI&callback=console.debug&libraries=maps,marker&v=beta">
    </script>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav> --}}
<x-app-layout>
    <x-slot name="header">
            @foreach($employeeName as $employee)
                <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Good morning, {{$employee->first_name}}
                </h1>
            @endforeach
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto text-black dark:text-black">
        <div class="pb-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg grid grid-cols-2 gap-4">
            <div class="text-center">
                <div class="schedule">
                    <div class="scheduleTableDiv">
                        <h3 class="text-white mb-4">Your addresses to visit today:</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-xl">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">SN</th>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Address</th>
                            </tr>
                            </thead>

                            @foreach($results as $result)
                                <tr>
                                    <td scope="col" class="px-2 py-2 border text-white-900 dark:border-gray-700">{{ $loop->index + 1 }}</td>
                                    <td scope="col" class="px-2 py-2 font-bold border text-white-900 dark:border-gray-700">{{ $result->first_name.' '.$result->last_name }}</td>
                                    <td scope="col" class="px-2 py-2 font-bold border text-white-900 dark:border-gray-700">{{ $result->street.' '.$result->number.', '.$result->city  }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <a class="redirect text-center" href="/enter_index_employee">
                            <x-primary-button type="button" class="mt-5">Go to index entry page</x-primary-button></a>
                    </div>
                </div>
            </div>
            <div>
                <div class="mapContainer">
                    <iframe class="map" src={{ $url }} allowfullscreen="" width="100%" height="500px"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>