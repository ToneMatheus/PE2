@extends('layouts/main_layout')

@section('content')
    <div class="py-8">
        <h1 class="text-2xl font-bold mb-4">Cron Jobs</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200 border-b">Name</th>
                        <th class="px-4 py-2 bg-gray-200 border-b">Description</th>
                        <th class="px-4 py-2 bg-gray-200 border-b">Scheduled Day</th>
                        <th class="px-4 py-2 bg-gray-200 border-b">Scheduled Time</th>
                        <th class="px-4 py-2 bg-gray-200 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cronjobs as $cronjob)
                        <tr>
                            <td class="px-4 py-2 border">{{ $cronjob->name }}</td>
                            <td class="px-4 py-2 border">{{ $cronjob->description }}</td>
                            <td class="px-4 py-2 border">{{ $cronjob->scheduled_day }}</td>
                            <td class="px-4 py-2 border">{{ $cronjob->scheduled_time }}</td>
                            <td class="px-4 py-2 border">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('run-cron-job', ['job' => $cronjob->name]) }}">
                                        @csrf
                                        <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">Run Job</button>
                                    </form>
                                    <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" href="{{ route('edit-cron-job', ['job' => $cronjob->name]) }}">edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection