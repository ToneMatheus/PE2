<x-app-layout :title="'ticket overview'">    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Display success message if it exists in the session -->
                    @if(session('success'))
                        <div class="bg-green-200 text-green-800 px-4 py-2 rounded-md mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="w-full bg-gray-200 dark:bg-gray-700 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">ID</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Name</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Email</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Issue</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Description</th>
                                <th class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">Solved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->id }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->name }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->email }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->issue }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">{{ $ticket->description }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm text-center">
                                    @if($ticket->status == 0)
                                        <p>The ticket is still open!</p>
                                        <td><a href="{{ route('closeticket', ['id' => $ticket->id]) }}">Close ticket</a></td>
                                        <td><a href="{{ route('edit', ['id' => $ticket->id]) }}">Edit</a></td>
                                    @else
                                        <p>The ticket is closed!</p>
                                        <td></td>
                                        <td></td>
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
