<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit '. $cronjob->name.' schedule' ) }}
    </h2>
</x-slot>

<x-slot name="backButton">
    <x-primary-anchor-button href="{{ route('index-cron-job')}}">
        <svg class="w-5 h-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
        </svg>
        <span>Go back</span>
    </x-primary-anchor-button>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    @if (session('success'))
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
            {{ __(session('success')) }}
        </p>
    @endif

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Job Scheduler
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update the schedule of this job 
                </p>
            </header>
            
            <form action="{{ route('store-schedule-cron-job', ['job' => $cronjob->name]) }}" method="POST" class="mt-6 space-y-6">
                @csrf

                <div class="max-w-xl">
                    <x-input-label for="name" :value="__('Name:')" />
                    <x-text-input readonly id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $cronjob->name)" autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div class="max-w-xl">
                    <x-input-label for="interval" :value="__('Interval:')" />
                    <x-select id="interval" name="interval" onchange="showFields()">
                        @if($cronjob->interval == null)
                            <option value="none">Choose an option:</option>
                        @endif  
                        <option value="yearly" {{ $cronjob->interval == "yearly" ? 'selected' : '' }}>Yearly</option>
                        <option value="monthly" {{ $cronjob->interval == "monthly" ? 'selected' : '' }}>Monthly</option>
                        <option value="daily" {{ $cronjob->interval == "daily" ? 'selected' : '' }}>Daily</option>
                    </x-select>
                    <x-input-error class="mt-2" :messages="$errors->get('interval')" />
                </div>

                <div id="scheduled_day_field" class="max-w-xl hidden">
                    <x-input-label for="scheduled_day" :value="__('Scheduled Day:')" />
                    <x-text-input id="scheduled_day" name="scheduled_day" type="number" class="mt-1 block w-full" min="1" max="28" :value="$cronjob->scheduled_day" />
                    <x-input-error class="mt-2" :messages="$errors->get('scheduled_day')" />
                </div>
                
                <div id="scheduled_month_field" class="max-w-xl hidden">
                    <x-input-label for="scheduled_month" :value="__('Scheduled Month:')" />
                    <x-select id="scheduled_month" name="scheduled_month">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $cronjob->scheduled_month == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </x-select>
                    <x-input-error class="mt-2" :messages="$errors->get('scheduled_month')" />
                </div>

                <div id="scheduled_time_field" class="max-w-xl hidden">
                    <x-input-label for="scheduled_time" :value="__('Scheduled Time:')" />
                    <x-text-input id="scheduled_time" name="scheduled_time" type="time" class="mt-1 block w-full" :value="$cronjob->scheduled_time" />
                    <x-input-error class="mt-2" :messages="$errors->get('scheduled_time')" />
                </div>
                <x-primary-button class="ml-1">
                    {{ __('Submit') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</div>




<script src="{{ asset('js/cron-jobs.js') }}"></script>
<script>
    // Function to run when the page loads
    window.onload = function() {
        showFields();
    };
</script>
</x-app-layout>