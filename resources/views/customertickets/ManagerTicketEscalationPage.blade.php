<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl dark:text-white leading-tight">
            <a href="{{ route('manager.TicketStatus') }}" class="mr-4 text-sm dark:text-white">Ticket overview Page</a>
            {{ __('Tickets Escalation Page') }}
        </h2>
    </x-slot>
@for ($i = 1; $i <= 3; $i++)
    <div class="w-4/5 mt-5 border-collapse bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white mx-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="p-2 bg-gray-200 dark:bg-gray-800 text-left">ID</th>
                    <th class="p-2 bg-gray-200 dark:bg-gray-800 text-left">Created At</th>
                    <th class="p-2 bg-gray-200 dark:bg-gray-800 text-left">Description</th>
                    <th class="p-2 bg-gray-200 dark:bg-gray-800 text-left">Line {{ $i }}</th>
                    <th class="p-2 bg-gray-200 dark:bg-gray-800 text-left">Urgency</th>
                    <th class="p-2 bg-gray-200 dark:bg-gray-800 text-left">Actions</th>
                </tr>
            </thead>
        </table>
        <div class="overflow-auto max-h-96">
            <table class="w-full">
                <tbody>
                    @foreach ($tickets->where('line', $i)->sortBy('created_at') as $ticket)
                        <tr class="bg-gray-50 dark:bg-gray-600">
                            <td class="p-2">{{ $ticket->id }}</td>
                            <td class="p-2 {{ $ticket->created_at->lt(Carbon\Carbon::now()->subMonth()) ? 'text-red-500' : ($ticket->created_at->lt(Carbon\Carbon::now()->subWeeks(2)) ? 'text-yellow-600' : 'text-gray-900 dark:text-white') }}">
                                {{ $ticket->created_at }}
                            </td>
                            <td class="p-2">{{ $ticket->description }}</td>
                            <td class="p-2">
                                <form class="update-form" action="{{ route('manager.tickets.update', $ticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="line" id="line" class="bg-gray-50 dark:bg-gray-600 text-gray-900 dark:text-white border-none">
                                        @foreach ($lines as $line)
                                            <option value="{{ $line }}" @if($ticket->line == $line) selected @endif>{{ $line }}</option>
                                        @endforeach
                                    </select>
                            </td>
                            <td class="p-2">
                                    <select name="urgency" id="urgency" class="bg-gray-50 dark:bg-gray-600 text-gray-900 dark:text-white border-none">
                                        @foreach ($urgencies as $urgency)
                                            <option value="{{ $urgency }}" @if($ticket->urgency == $urgency) selected @endif>{{ $urgency }}</option>
                                        @endforeach
                                    </select>
                            </td>
                            <td class="p-2">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endfor

            
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('.update-form').on('submit', function(e) {
                        e.preventDefault();
            
                        var form = $(this);
                        var url = form.attr('action');
            
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: form.serialize() + '&_method=PUT',
                            success: function(data) {
                                if (data.error) {
                                    alert('Error: ' + data.error);
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    });
                });
            </script>

</x-app-layout>