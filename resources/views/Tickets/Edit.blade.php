<x-app-layout :title="'ticket overview'">
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> <!-- Enlarged max-w-full -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Issue</th>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Solved</th>
                                <th class="px-4 py-3 sm:py-4 sm:px-6 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Submit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($tickets as $ticket)
                            <tr>
                                <form method="POST" action="{{ route('edit.update', ['id' => $ticket->id]) }}">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="id" value="{{ $ticket->id }}">

                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $ticket->id }}</td>
                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900"><input type="text" name="name" value="{{ $ticket->name }}" class="border rounded-md px-2 py-1"></td>
                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900"><input type="text" name="email" value="{{ $ticket->email }}" class="border rounded-md px-2 py-1"></td>
                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900"><input type="text" name="issue" value="{{ $ticket->issue }}" class="border rounded-md px-2 py-1"></td>
                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900"><input type="text" name="description" value="{{ $ticket->description }}" class="border rounded-md px-2 py-1"></td>
                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900"><input type="text" name="Solved" value="{{ $ticket->is_solved }}" class="border rounded-md px-2 py-1"></td>
                                    <td class="px-4 py-3 sm:py-4 sm:px-6 whitespace-nowrap text-xs sm:text-sm text-gray-900"><button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button></td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
