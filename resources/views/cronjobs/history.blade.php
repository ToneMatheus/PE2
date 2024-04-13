<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Job Logs') }}
    </h2>
</x-slot>
<div class="fixed top-36 left-10">
    <button onclick="window.history.back();" class="text-blue-500 hover:text-blue-700">Back</button>
</div>
<div class="flex flex-col sm:flex-row">
    <div class="w-full sm:w-1/4">
        <div class="sm:py-8 py-2 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-5 ">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
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
                                <x-select id="JobRun" name="JobRun"> 
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
                            <x-select id="LogLevel" name="LogLevel"> 
                                <option value="All">All</option>
                                <option value="Info">Information</option>
                                <option value="Warning">Warning</option>
                                <option value="Critical">Critical</option>
                                <option value="Error">Error</option>
                            </x-select>
                        </div>
               
                        <x-primary-button class="ml-1 py-2" onclick="onApplyFilters()">
                            {{ __('Apply') }}
                        </x-primary-button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full sm:w-3/4">
        <div class="sm:py-8 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-5">
                <div class="p-2 sm:p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        @include('cronjobs.parts.logs', ['jobLogs' => $jobLogs])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

<script src="{{ asset('js/cron-jobs.js') }}"></script>
</x-app-layout>