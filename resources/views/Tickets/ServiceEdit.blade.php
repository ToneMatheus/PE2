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

                    <table class="w-full bg-gray-200 dark:bg-gray-700 rounded-lg shadow mb-6">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Issue</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Description</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Created</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Urgency</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-100 dark:bg-gray-600">
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->issue }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->description }}</td>
                                <td class="px-4 py-2 text-sm text-center {{ now()->diffInDays($ticket->created_at) > 7 ? 'text-red-500' : '' }}">
                                    {{ $ticket->created_at }}
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->urgency }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">
                                    @if($ticket->active == 1)
                                        <span class="text-green-500">Open</span>
                                    @else
                                        <span class="text-red-500">Closed</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <form method="POST" action="{{ route('ServiceEdit.update', ['id' => $ticket->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="resolution" class="block text-sm font-medium text-gray-700">Resolution</label>
                                <input type="text" name="resolution" id="resolution" value="{{ $ticket->resolution }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300">
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                                <a href="{{ route('ServiceTicketOverview') }}" class="w-full text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>