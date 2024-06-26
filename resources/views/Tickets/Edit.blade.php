<!-- resources/views/tickets/edit.blade.php -->
<x-app-layout :title="'Edit Ticket'">
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('edit.update', ['id' => $ticket->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ $ticket->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-200">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ $ticket->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-200">
                            </div>
                            <div>
                                <label for="issue" class="block text-sm font-medium text-gray-700">Issue</label>
                                <input type="text" name="issue" id="issue" value="{{ $ticket->issue }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-200">
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <input type="text" name="description" id="description" value="{{ $ticket->description }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-200">
                            </div>
                            <div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                                <a href="{{ route('managerticketoverview') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
