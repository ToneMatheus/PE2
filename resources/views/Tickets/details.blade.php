<!-- resources/views/tickets/overview.blade.php -->
<x-app-layout :title="'Ticket Overview'">    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full bg-gray-200 dark:bg-gray-700 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Issue</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Description</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Solution</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Created</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Urgency</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Status</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-100 dark:bg-gray-600">
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->issue }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->description }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->resolution }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->created_at }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->urgency }}</td>
                                @if($ticket->active == 1)
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">The ticket is still open!</td>
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center"><a href="{{ route('UserEdit', ['id' => $ticket->id]) }}" class="text-blue-500 hover:text-blue-700">Edit</a></td>
                                @else
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">The ticket is closed!</td>
                                    <td class="px-4 py-2"></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex space-x-4 justify-center mt-6">
                        <a href="{{ route('ticket_overview') }}" class="w-full md:w-auto text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
