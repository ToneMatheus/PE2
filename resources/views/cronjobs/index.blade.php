<x-app-layout title="Cron-Jobs">
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Job Scheduler') }}
    </h2>
</x-slot>

<div class="py-8 dark:text-white">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div id="scheduled_toast" class="hidden bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
        <!-- Toast message content will be added here dynamically -->
        </div>
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <caption class="p-2 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                        Scheduled Jobs
                    </caption>
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name:</th>
                            <th scope="col" class="px-6 py-3">Time Interval</th>
                            <th scope="col" class="px-6 py-3">Scheduled Day</th>
                            <th scope="col" class="px-6 py-3">Scheduled Month</th>
                            <th scope="col" class="px-6 py-3">Scheduled Time</th>
                            <th scope="col" class="px-6 py-3">Log level</th>
                            <th scope="col" class="px-6 py-3">Is Enabled</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($scheduledJobs) == 0)
                        <tr>
                            <td class="px-2 py-2 border dark:border-gray-700" colspan="6">There are no jobs scheduled</td>
                        </tr>     
                    @else
                        @foreach ($scheduledJobs as $job)
                            <tr>
                                <td class="px-2 py-2 border dark:border-gray-700">{{ $job->name }}</td>
                                <td class="px-2 py-2 border dark:border-gray-700">{{ $job->interval }}</td>
                                <td class="px-2 py-2 border dark:border-gray-700">{{ $job->scheduled_day }}</td>
                                <td class="px-2 py-2 border dark:border-gray-700">
                                    @if ($job->scheduled_month !== null)
                                        {{ date('F', mktime(0, 0, 0, $job->scheduled_month, 10)) }}
                                    @endif
                                </td>
                                <td class="px-2 py-2 border dark:border-gray-700">{{ $job->scheduled_time }}</td>
                                <td class="border dark:border-gray-700">
                                <select name="log" onchange="updateLogLevel(true,'{{ $job->name }}', this.value)" class="block py-2 border-none dark:bg-gray-800 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                        <option value="0" {{ $job->log_level == 0 ? 'selected' : '' }}>Debug</option>
                                        <option value="1" {{ $job->log_level == 1 ? 'selected' : '' }}>Info</option>
                                        <option value="2" {{ $job->log_level == 2 ? 'selected' : '' }}>Warning</option>
                                        <option value="3" {{ $job->log_level == 3 ? 'selected' : '' }}>Critical</option>
                                        <option value="4" {{ $job->log_level == 4 ? 'selected' : '' }}>Error</option>
                                </select>
                                </td>
                                <td class="px-2 py-2 border dark:border-gray-700">{{ $job->is_enabled ? 'Yes' : 'No' }}</td>
                                <td class="px-2 py-2 border dark:border-gray-700">
                                    <div class="flex gap-2">
                                        <x-primary-anchor-button href="{{ route('job.history', ['job' => $job->name]) }}" class="ml-1">
                                            {{ __('View History') }}
                                        </x-primary-anchor-button>
                                        <form method="POST" action="{{ route('run-cron-job', ['job' => $job->name]) }}">
                                        <input type="hidden" id="logLevelInput" name="logLevel" value="{{ $job->log_level }}">
                                        @csrf
                                        <x-primary-button class="ml-1">
                                            {{ __('Run Job') }}
                                        </x-primary-button>
                                        </form>
                                        
                                        <x-primary-anchor-button href="{{ route('edit-schedule-cron-job', ['job' => $job->name]) }}" class="ml-1">
                                            {{ __('Edit') }}
                                        </x-primary-anchor-button>
                                        <form method="POST" action="{{ route('toggle-schedule-cron-job', ['job' => $job->name]) }}">
                                            @csrf
                                            @if ($job->is_enabled)
                                                <x-primary-button class="ml-1 dark:hover:bg-red-500 dark:bg-red-700 dark:text-white">
                                                    {{ __('Disable') }}
                                                </x-primary-button>
                                            @else
                                                <x-primary-button class="ml-1">
                                                    {{ __('Enable') }}
                                                </x-primary-button>
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
        </div>

        <div id="unscheduled_toast" class="hidden bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
        <!-- Toast message content will be added here dynamically -->
        </div>
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <caption class="p-2 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                        Unscheduled Jobs
                    </caption>
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name:</th>
                            <th scope="col" class="px-6 py-3">Log level:</th>
                            <th scope="col" class="px-6 py-3">Actions:</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($unscheduledJobs) == 0)
                        <tr>
                            <td class="px-2 py-2 border dark:border-gray-700" colspan="2">There are no jobs scheduled</td>
                        </tr>     
                    @else
                        @foreach ($unscheduledJobs as $job)
                            <tr>
                                <td class="px-2 py-2 border dark:border-gray-700">{{ $job->name }}</td>
                                <td class="border dark:border-gray-700">
                                <select name="log" onchange="updateLogLevel(false, '{{ $job->name }}', this.value)" class="block py-2 border-none dark:bg-gray-800 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                        <option value="0" {{ $job->log_level == 0 ? 'selected' : '' }}>Debug</option>
                                        <option value="1" {{ $job->log_level == 1 ? 'selected' : '' }}>Info</option>
                                        <option value="2" {{ $job->log_level == 2 ? 'selected' : '' }}>Warning</option>
                                        <option value="3" {{ $job->log_level == 3 ? 'selected' : '' }}>Critical</option>
                                        <option value="4" {{ $job->log_level == 4 ? 'selected' : '' }}>Error</option>
                                </select>
                                </td>
                                <td class="px-2 py-2 border dark:border-gray-700">
                                    <div class="flex gap-2">
                                        
                                    <x-primary-anchor-button href="{{ route('job.history', ['job' => $job->name]) }}" class="ml-1">
                                        {{ __('View History') }}
                                    </x-primary-anchor-button>
                                        
                                    <form method="POST" action="{{ route('run-cron-job', ['job' => $job->name]) }}">
                                        <input type="hidden" id="logInput_{{ $job->name }}" name="logInput" value="{{ $job->log_level }}">
                                        @csrf
                                        <x-primary-button class="ml-1">
                                            {{ __('Run Job') }}
                                        </x-primary-button>
                                    </form>

                                    <x-primary-anchor-button href="{{ route('edit-schedule-cron-job', ['job' => $job->name]) }}" class="ml-1">
                                        {{ __('Add Schedule') }}
                                    </x-primary-anchor-button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/cron-jobs.js') }}"></script>
</x-app-layout>