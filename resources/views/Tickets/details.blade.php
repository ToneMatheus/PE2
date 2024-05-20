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
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->Urgency }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
