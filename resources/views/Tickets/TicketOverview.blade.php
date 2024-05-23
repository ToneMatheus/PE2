<x-app-layout :title="'ticket overview'">    
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->id }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->name }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">@if($ticket->status == 0)
                                    <p>The ticket is still open!</p>
                                    @else
                                    <p>The ticket is closed!</p>
                                    @endif
                                </td>
                                <td><a href="{{ route('details', ['id' => $ticket->id]) }}">Details</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
