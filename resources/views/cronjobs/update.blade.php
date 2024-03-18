@extends('layouts/main_layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Edit Job</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('update-cron-job', ['job' => $cronjob->name]) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') {{-- Add this line to indicate it's a PUT request --}}
            <div>
                <label for="name" class="block font-semibold">Name:</label>
                <input disabled type="text" id="name" name="name" value="{{ $cronjob->name }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="description" class="block font-semibold">Description:</label>
                <textarea id="description" name="description" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">{{ $cronjob->description }}</textarea>
            </div>

            <div>
                <label for="scheduled_day" class="block font-semibold">Scheduled Day:</label>
                <input type="number" id="scheduled_day" min="1" max="28" name="scheduled_day" value="{{ $cronjob->scheduled_day }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="scheduled_time" class="block font-semibold">Scheduled Time:</label>
                <input type="time" id="scheduled_time" name="scheduled_time" value="{{ $cronjob->scheduled_time }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
        </form>
    </div>
@endsection