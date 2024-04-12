<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Job Scheduler') }}
    </h2>
</x-slot>

<div class="py-8 dark:text-white">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Scheduled Jobs</h2>
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Name</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Time Interval</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Scheduled Day</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Scheduled Month</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Scheduled Time</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Is Enabled</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($scheduledJobs) == 0)
                        <tr>
                            <td class="px-4 py-2 border dark:border-gray-700" colspan="6">There are no jobs scheduled</td>
                        </tr>     
                    @else
                        @foreach ($scheduledJobs as $job)
                            <tr>
                                <td class="px-4 py-2 border dark:border-gray-700">{{ $job->name }}</td>
                                <td class="px-4 py-2 border dark:border-gray-700">{{ $job->interval }}</td>
                                <td class="px-4 py-2 border dark:border-gray-700">{{ $job->scheduled_day }}</td>
                                <td class="px-4 py-2 border dark:border-gray-700">
                                    @if ($job->scheduled_month !== null)
                                        {{ date('F', mktime(0, 0, 0, $job->scheduled_month, 10)) }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 border dark:border-gray-700">{{ $job->scheduled_time }}</td>
                                <td class="px-4 py-2 border dark:border-gray-700">{{ $job->is_enabled ? 'Yes' : 'No' }}</td>
                                <td class="px-4 py-2 border dark:border-gray-700">
                                    <div class="flex gap-2">
                                        <x-primary-anchor-button href="{{ route('job-history') }}" class="ml-1">
                                            {{ __('View History') }}
                                        </x-primary-anchor-button>
                                        <form method="POST" action="{{ route('run-cron-job', ['job' => $job->name]) }}">
                                            @csrf
                                            <x-primary-button class="ml-2">
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

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Unscheduled Jobs</h2>
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Name</th>
                            <th class="px-4 py-2 bg-gray-200 dark:bg-gray-700 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($unscheduledJobs) == 0)
                        <tr>
                            <td class="px-4 py-2 border dark:border-gray-700" colspan="2">There are no jobs scheduled</td>
                        </tr>     
                    @else
                        @foreach ($unscheduledJobs as $job)
                            <tr>
                                <td class="px-4 py-2 border dark:border-gray-700">{{ $job }}</td>
                                <td class="px-4 py-2 border dark:border-gray-700">
                                    <div class="flex gap-2">
                                        
                                    <x-primary-anchor-button href="{{ route('job-history') }}" class="ml-1">
                                        {{ __('View History') }}
                                    </x-primary-anchor-button>
                                        
                                    <form method="POST" action="{{ route('run-cron-job', ['job' => $job]) }}">
                                        @csrf
                                        <x-primary-button class="ml-1">
                                            {{ __('Run Job') }}
                                        </x-primary-button>
                                    </form>

                                    <x-primary-anchor-button href="{{ route('edit-schedule-cron-job', ['job' => $job]) }}" class="ml-1">
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