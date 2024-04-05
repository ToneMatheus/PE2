<x-app-layout>
<div class="py-8">
    <div class="history-modal fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="history-content bg-white p-6 rounded-lg shadow-lg">
            <!-- History data will be loaded here via JavaScript -->
        </div>
    </div>
    <h1 class="text-2xl font-bold mb-4">Scheduled Jobs</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-200 border-b">Name</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Time Interval</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Scheduled Day</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Scheduled Month</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Scheduled Time</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Is Enabled</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (count($scheduledJobs) == 0)
                <tr>
                    <td class="px-4 py-2 border" colspan="6">There are no jobs scheduled</td>
                </tr>     
            @else
                @foreach ($scheduledJobs as $job)
                    <tr>
                        <td class="px-4 py-2 border">{{ $job->name }}</td>
                        <td class="px-4 py-2 border">{{ $job->interval }}</td>
                        <td class="px-4 py-2 border">{{ $job->scheduled_day }}</td>
                        <td class="px-4 py-2 border">
                            @if ($job->scheduled_month !== null)
                                {{ date('F', mktime(0, 0, 0, $job->scheduled_month, 10)) }}
                            @endif
                        </td>
                        <td class="px-4 py-2 border">{{ $job->scheduled_time }}</td>
                        <td class="px-4 py-2 border">{{ $job->is_enabled ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-2 border">
                            <div class="flex gap-2">
                                <button onclick="showHistory('{{ $job->name }}')" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">View History</button>
                                <form method="POST" action="{{ route('run-cron-job', ['job' => $job->name]) }}">
                                    @csrf
                                    <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">Run Job</button>
                                </form>
                                <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" href="{{ route('edit-schedule-cron-job', ['job' => $job->name]) }}">edit</a>
                                <form method="POST" action="{{ route('toggle-schedule-cron-job', ['job' => $job->name]) }}">
                                    @csrf
                                    @if ($job->is_enabled)
                                        <button class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded" type="submit">disable</button>
                                    @else
                                        <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">enable</button>
                                    @endif
                                    
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="py-8">
    <h1 class="text-2xl font-bold mb-4">Unscheduled Jobs</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-200 border-b">Name</th>
                    <th class="px-4 py-2 bg-gray-200 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (count($unscheduledJobs) == 0)
                <tr>
                    <td class="px-4 py-2 border" colspan="2">There are no jobs scheduled</td>
                </tr>     
            @else
                @foreach ($unscheduledJobs as $job)
                    <tr>
                        <td class="px-4 py-2 border">{{ $job }}</td>
                        <td class="px-4 py-2 border">
                            <div class="flex gap-2">
                                <button onclick="showHistory('{{ $job }}')" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">View History</button>
                                <form method="POST" action="{{ route('run-cron-job', ['job' => $job]) }}">
                                    @csrf
                                    <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="submit">Run Job</button>
                                </form>
                                <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" href="{{ route('edit-schedule-cron-job', ['job' => $job]) }}">Add Schedule</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('js/cron-jobs.js') }}"></script>
</x-app-layout>