<x-app-layout>
    <style>
        @layer utilities {
            .h-screen-minus-header {
                height: calc(100vh - 64px);
            }
            .grid-cols-2-custom {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .grid-cols-3-custom {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .closed-column-hidden {
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
            transform: translateX(100%);
            opacity: 0;
        }
        .closed-column-visible {
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
            transform: translateX(0);
            opacity: 1;
        }
        
        #toggleClosedTickets {
            position: fixed;
            bottom: 4rem;
            right: 2rem;
            z-index: 999;
        }
    </style>
    
    <div id="ticketsGrid" class="grid grid-cols-3-custom gap-4 h-screen-minus-header">
        <div class="bg-gray-200 p-4 overflow-y-scroll h-screen-minus-header">
            <div class="mb-4">open tickets &#40;{{ count($tickets) }}&#41;</div>

            <form method="GET" action="{{ route('filter_tickets') }}" class="mb-5">
                <div class="grid grid-cols-3 gap-4 mb-3">
                    <input type="hidden" name="filter" value="own_tickets">
                    <input type="hidden" name="urgency_own" value="{{ request('urgency_own') }}">
                    <input type="hidden" name="sort_own" value="{{ request('sort_own') }}">
                    <div>
                        <label for="helpline" class="block text-sm font-medium text-gray-700">Helpline</label>
                        <select name="helpline" id="helpline" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Helplines</option>
                            <option value="1" {{ request('helpline') == 1 ? 'selected' : '' }}>Helpline 1</option>
                            <option value="2" {{ request('helpline') == 2 ? 'selected' : '' }}>Helpline 2</option>
                            <option value="3" {{ request('helpline') == 3 ? 'selected' : '' }}>Helpline 3</option>
                        </select>
                    </div>
                    <div>
                        <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
                        <select name="urgency" id="urgency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Urgencies</option>
                            <option value="0" {{ request('urgency') == '0' ? 'selected' : '' }}>Low</option>
                            <option value="1" {{ request('urgency') == '1' ? 'selected' : '' }}>Medium</option>
                            <option value="2" {{ request('urgency') == '2' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700">Sort by</label>
                        <select name="sort" id="sort" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                            <option value="urgency_desc" {{ request('sort') == 'urgency_desc' ? 'selected' : '' }}>Highest Urgency First</option>
                            <option value="urgency_asc" {{ request('sort') == 'urgency_asc' ? 'selected' : '' }}>Lowest Urgency First</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                </div>
            </form>

            <div class="overflow-y-auto">
                @foreach($tickets as $ticket)
                    @php
                        $daysOpen = $ticket->created_at->diffInDays(now());
                    @endphp
                    <div class="mb-3 border border-gray-500 border-solid p-4">
                        <div class="flex flex-row justify-between">
                            <div>name: {{ $ticket->name}}</div>
                            <div>
                                <div>date: {{ $ticket->created_at->format('Y-m-d')}}</div>
                                <div class="text-gray-600 text-sm">Open for {{ $daysOpen }} days</div>
                            </div>
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
            <div class="mb-4">Assigned to me &#40;{{ count($own_tickets) }}&#41;</div>

            <form method="GET" action="{{ route('filter_tickets') }}" class="mb-5">
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <input type="hidden" name="filter" value="tickets">
                    <input type="hidden" name="filter" value="own_tickets">
                    <input type="hidden" name="helpline" value="{{ request('helpline') }}">
                    <input type="hidden" name="urgency" value="{{ request('urgency') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <div>
                        <label for="urgency_own" class="block text-sm font-medium text-gray-700">Urgency</label>
                        <select name="urgency_own" id="urgency_own" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Urgencies</option>
                            <option value="0" {{ request('urgency_own') == '0' ? 'selected' : '' }}>Low</option>
                            <option value="1" {{ request('urgency_own') == '1' ? 'selected' : '' }}>Medium</option>
                            <option value="2" {{ request('urgency_own') == '2' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort_own" class="block text-sm font-medium text-gray-700">Sort by</label>
                        <select name="sort_own" id="sort_own" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="created_at_desc" {{ request('sort_own') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_at_asc" {{ request('sort_own') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                            <option value="urgency_desc" {{ request('sort_own') == 'urgency_desc' ? 'selected' : '' }}>Highest Urgency First</option>
                            <option value="urgency_asc" {{ request('sort_own') == 'urgency_asc' ? 'selected' : '' }}>Lowest Urgency First</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                </div>
            </form>





            
            @foreach($own_tickets as $ticket)
                @php
                    $daysOpen = $ticket->created_at->diffInDays(now());
                @endphp
                <div class="mb-3 border border-gray-500 border-solid p-4">
                    <div class="flex flex-row justify-between">
                        <div>name: {{ $ticket->name}}</div>
                        <div>
                            <div>date: {{ $ticket->created_at->format('Y-m-d')}}</div>
                            <div class="text-gray-600 text-sm">Open for {{ $daysOpen }} days</div>
                        </div>
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

        <div id="closedTicketsColumn" class="bg-gray-200 p-4 overflow-y-scroll h-screen-minus-header closed-column-visible">
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
    <button id="toggleClosedTickets" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Toggle Closed Tickets</button>

    <script>
        // Functie om te controleren of de kolom gesloten is
        function isClosedColumnHidden() {
            // Controleer of de hash '#closed' in de URL staat
            return window.location.hash === '#closed';
        }

        // Functie om de status van de gesloten kolom op te slaan in de URL-hash
        function saveClosedColumnState(isHidden) {
            // Bijwerken van de URL-hash op basis van de kolomstatus
            window.location.hash = isHidden ? 'closed' : '';
        }

        // Event listener voor de knop
        document.getElementById('toggleClosedTickets').addEventListener('click', function () {
            var closedTicketsColumn = document.getElementById('closedTicketsColumn');
            var ticketsGrid = document.getElementById('ticketsGrid');
            var isHidden = isClosedColumnHidden(); // Controleer de status

            if (isHidden) { // Als de kolom verborgen is
                closedTicketsColumn.classList.remove('closed-column-hidden');
                closedTicketsColumn.classList.add('closed-column-visible');
                ticketsGrid.classList.remove('grid-cols-2-custom');
                ticketsGrid.classList.add('grid-cols-3-custom');
            } else { // Als de kolom zichtbaar is
                closedTicketsColumn.classList.remove('closed-column-visible');
                closedTicketsColumn.classList.add('closed-column-hidden');
                setTimeout(function() {
                    ticketsGrid.classList.remove('grid-cols-3-custom');
                    ticketsGrid.classList.add('grid-cols-2-custom');
                }, 500);
            }

            // Toggle de status en sla deze op in de URL-hash
            saveClosedColumnState(!isHidden);
        });

        // Voer bij het laden van de pagina de juiste actie uit op basis van de opgeslagen status
        window.onload = function () {
            var isHidden = isClosedColumnHidden(); // Controleer de status
            var closedTicketsColumn = document.getElementById('closedTicketsColumn');
            var ticketsGrid = document.getElementById('ticketsGrid');

            if (isHidden) { // Als de kolom verborgen is
                closedTicketsColumn.classList.add('closed-column-hidden');
                ticketsGrid.classList.add('grid-cols-2-custom');
            } else { // Als de kolom zichtbaar is
                closedTicketsColumn.classList.add('closed-column-visible');
                ticketsGrid.classList.add('grid-cols-3-custom');
            }
        };
    </script>
</x-app-layout>