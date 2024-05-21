<x-app-layout>
    <style>
        @layer utilities {
            .h-screen-minus-header {
                height: calc(100vh - 64px);
            }
        }
    </style>
    <div class="grid grid-cols-3 gap-4" style="height:calc(100vh-64px);">
        <div class="bg-gray-200 p-4 overflow-y-scroll h-screen-minus-header">
            {{--<!-- <div class="bg-gray-200 p-4 overflow-y-scroll h-screen" style="height:calc(100vh-64px);"> -->--}}

            <div class="mb-5">open tickets &#40;{{ count($tickets) }}&#41;</div>
            {{-- //todo filters maken om te filteren op welke helplijn je tickets wil zien --}}

            <form method="GET" action="{{ route('filter_tickets') }}" class="mb-5">
                <div class="mb-3">
                    <label for="helpline" class="block text-sm font-medium text-gray-700">Filter by Helpline</label>
                    <select name="helpline" id="helpline" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Helplines</option>
                        <option value="1" {{ request('helpline') == 1 ? 'selected' : '' }}>Helpline 1</option>
                        <option value="2" {{ request('helpline') == 2 ? 'selected' : '' }}>Helpline 2</option>
                        <option value="3" {{ request('helpline') == 3 ? 'selected' : '' }}>Helpline 3</option>
                    </select>
                </div>
                <div class="mb-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                </div>
            </form>


            <div class="overflow-y-auto">
                @foreach($tickets as $ticket)
                <div class="mb-3 border border-gray-500 border-solid p-4">
                    <div class="flex flex-row justify-between">
                        <div>name: {{ $ticket->name}}</div>
                        <div>date: {{ $ticket->created_at->format('Y-m-d')}}</div>
                    </div>
                    <div>issue: {{ $ticket->issue}}</div>
                    <div class="flex mt-2">
                        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mr-auto cursor-pointer">More info</a>
                        <form action="{{ route('assign_ticket', ['id' => $ticket->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mr-auto cursor-pointer">Assign yourself</button>
                        </form>

                    </div>



                </div>
                @endforeach
            </div>


        </div>


        <div class="bg-gray-200 p-4 overflow-y-scroll h-screen-minus-header">
            <div>Assigned to me &#40;{{ count($own_tickets) }}&#41;</div>


            @foreach($own_tickets as $ticket)
            <div class="mb-3 border border-gray-500 border-solid p-4">
                <div class="flex flex-row justify-between">
                    <div>name: {{ $ticket->name}}</div>
                    <div>date: {{ $ticket->created_at->format('Y-m-d')}}</div>
                </div>
                <div>issue: {{ $ticket->issue}}</div>
                <div class="flex mt-2">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-auto">More info</button>

                    <form action="{{ route('unassign_ticket', ['id' => $ticket->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mr-auto cursor-pointer">Unassign yourself</button>
                    </form>
                </div>

            </div>
            @endforeach

        </div>





        <div class="bg-gray-200 p-4 overflow-y-scroll h-screen-minus-header">
            <div>Closed tickets &#40;{{ count($tickets_closed) }}&#41;</div>

            @foreach($tickets_closed as $ticket)
            <div class="mb-3 border border-gray-500 border-solid p-4">
                <div class="flex flex-row justify-between">
                    <div>name: {{ $ticket->name}}</div>
                    <div>date: {{ $ticket->created_at->format('Y-m-d')}}</div>
                </div>
                <div>issue: {{ $ticket->issue}}</div>
                <div class="flex mt-2">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-auto">More info</button>

                </div>

            </div>
            @endforeach

        </div>





    </div>
</x-app-layout>