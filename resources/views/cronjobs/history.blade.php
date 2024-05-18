<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Job Logs') }}
        </h2>
    </x-slot>

    <x-slot name="backButton">
        <x-primary-anchor-button href="{{ route('index-cron-job')}}">
            <svg class="w-5 h-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
            </svg>
            <span class="p-1">Go back</span>
        </x-primary-anchor-button>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row">
            <div class="w-full sm:w-1/4 px-2">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 pb-2">Filter</h2>
                    <div class="max-w-xl py-2">
                        <x-input-label for="Job" :value="__('Job:')" />
                        <x-select id="Job" name="Job" onchange="OnchangeJob()">
                            @foreach ($jobs as $job)
                            @if ($job == $paramJob)
                            <option selected value="{{ $job}}">{{ $job}}</option>
                            @else
                            <option value="{{ $job}}">{{ $job}}</option>
                            @endif
                            @endforeach
                        </x-select>
                    </div>

                    <div class="max-w-xl py-2">
                        <x-input-label for="JobRun" :value="__('Run:')" />
                        @if ($jobRuns->count() > 0)
                        <x-select id="JobRun" name="JobRun" onchange="onApplyFilters()">
                            @foreach ($jobRuns->reverse() as $index => $jobRun)
                            <option value="{{ $jobRun->id }}">Run: {{ $index + 1 }} - {{ $jobRun->started_at }}</option>
                            @endforeach
                        </x-select>
                        @else
                        <x-select disabled id="JobRun" name="JobRun">
                            <option selected>No runs available</option>
                        </x-select>
                        @endif
                    </div>

                    <div class="max-w-xl py-2">
                        <x-input-label for="LogLevel" :value="__('Log Level:')" />
                        <x-select id="LogLevel" name="LogLevel" onchange="onApplyFilters()">
                            <option value="All">All</option>
                            <option value="Debug">Debug</option>
                            <option value="Info">Information</option>
                            <option value="Warning">Warning</option>
                            <option value="Critical">Critical</option>
                            <option value="Error">Error</option>
                        </x-select>
                    </div>
                </div>
            </div>
            <div class="w-full sm:w-3/4 px-2">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div id="Logs" class="relative overflow-x-auto sm:rounded-lg">
                        @include('cronjobs.parts.logs', ['jobRun' => $jobRuns->reverse()->first()])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/cron-jobs.js') }}"></script>
</x-app-layout>