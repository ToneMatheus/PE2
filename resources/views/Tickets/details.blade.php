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
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Status</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Solution</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Created</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Urgency</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->issue }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->description }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">
                                    @if($ticket->active == 1)
                                        <p>The ticket is still open!</p>
                                    @else
                                        <p>The ticket is closed!</p>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->resolution }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->created_at }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->urgency }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">
                                    @if($ticket->employee_id == Auth::id() && $ticket->status == 0)
                                        <a href="{{ route('closeticket', ['id' => $ticket->id]) }}" class="text-blue-500 hover:text-blue-700">Close Ticket</a>
                                        <span class="mx-2">|</span>
                                        <a href="{{ route('ServiceEdit', ['id' => $ticket->id]) }}" class="text-blue-500 hover:text-blue-700">Solve</a>
                                        
                                    @else
                                        <p class="text-gray-400">No actions available</p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
