<x-app-layout :title="'Ticket Overview'">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full bg-gray-200 dark:bg-gray-700 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">ID</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Name</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Status</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Close Ticket</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr class="bg-gray-100 dark:bg-gray-600">
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->id }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">{{ $ticket->name }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">
                                    @if($ticket->status == 0)
                                        <span class="text-green-500">Open</span>
                                    @else
                                        <span class="text-red-500">Closed</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">
                                    @if($ticket->status == 0)
                                        <a href="{{ route('closeticket', ['id' => $ticket->id]) }}" class="text-blue-500 hover:text-blue-700">Close Ticket</a>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-center">
                                    @if($ticket->status == 0)
                                        <a href="{{ route('ServiceEdit', ['id' => $ticket->id]) }}" class="text-blue-500 hover:text-blue-700">Solve</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>