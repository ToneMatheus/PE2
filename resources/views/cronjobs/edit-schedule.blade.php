@extends('layouts/main_layout')
<script src="{{ asset('js/cron-jobs.js') }}"></script>

@section('content')
    <div class="max-w-2xl mx-auto">
    <a href="{{ route('index-cron-job')}}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="margin-right: 10px;">back</a>
        <h1 class="text-2xl font-bold mb-4">Edit Job</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('store-schedule-cron-job', ['job' => $cronjob->name]) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block font-semibold">Name:</label>
                <input readonly type="text" id="name" name="name" value="{{ $cronjob->name }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="interval" class="block font-semibold">Interval:</label>
                <select id="interval" name="interval" onchange="showFields()" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                @if($cronjob->interval == null)
                    <option value="none">Choose an option:</option>
                @endif  
                    <option value="yearly" {{ $cronjob->interval == "yearly" ? 'selected' : '' }}>Yearly</option>
                    <option value="monthly" {{ $cronjob->interval == "monthly" ? 'selected' : '' }}>Monthly</option>
                    <option value="daily" {{ $cronjob->interval == "daily" ? 'selected' : '' }}>Daily</option>
                </select>
            </div>

            <div id="scheduled_day_field" class="hidden">
                <label for="scheduled_day" class="block font-semibold">Scheduled Day:</label>
                <input type="number" id="scheduled_day" min="1" max="28" name="scheduled_day" value="{{ $cronjob->scheduled_day }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>
            
            <div id="scheduled_month_field" class="hidden">
                <label for="scheduled_month" class="block font-semibold">Scheduled Month:</label>
                <select id="scheduled_month" name="scheduled_month" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    <option value="1" {{ $cronjob->scheduled_month == 1 ? 'selected' : '' }}>January</option>
                    <option value="2" {{ $cronjob->scheduled_month == 2 ? 'selected' : '' }}>February</option>
                    <option value="3" {{ $cronjob->scheduled_month == 3 ? 'selected' : '' }}>March</option>
                    <option value="4" {{ $cronjob->scheduled_month == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $cronjob->scheduled_month == 5 ? 'selected' : '' }}>May</option>
                    <option value="6" {{ $cronjob->scheduled_month == 6 ? 'selected' : '' }}>June</option>
                    <option value="7" {{ $cronjob->scheduled_month == 7 ? 'selected' : '' }}>July</option>
                    <option value="8" {{ $cronjob->scheduled_month == 8 ? 'selected' : '' }}>August</option>
                    <option value="9" {{ $cronjob->scheduled_month == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $cronjob->scheduled_month == 10 ? 'selected' : '' }}>October</option>
                    <option value="11" {{ $cronjob->scheduled_month == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $cronjob->scheduled_month == 12 ? 'selected' : '' }}>December</option>
                </select>
            </div>

            <div id="scheduled_time_field" class="hidden">
                <label for="scheduled_time" class="block font-semibold">Scheduled Time:</label>
                <input type="time" id="scheduled_time" name="scheduled_time" value="{{ $cronjob->scheduled_time }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
        </form>
    </div>

    <script>
        // Function to run when the page loads
        window.onload = function() {
            showFields();
        };
    </script>
@endsection