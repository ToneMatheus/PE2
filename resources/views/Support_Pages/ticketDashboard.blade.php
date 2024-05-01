<x-app-layout>
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-200 p-4 h-100">
            <div class="mb-5">open tickets</div>

            <div class="bg-green-200 mb-3">
                <div class="flex flex-row justify-between">
                    <div>name: karen</div>
                    <div>date: 20-4-2024</div>
                </div>
                <div>issue: broken</div>
                <div>
                    description: veni vidi vichi
                </div>

            </div>

            @foreach($tickets as $ticket)

            <div class="mb-3 border border-gray-500 border-solid p-4">
                <div class="flex flex-row justify-between">
                    <div>name: {{ $ticket->name}}</div>
                    <div>date: {{ $ticket->created_at}}</div>
                </div>
                <div>issue: {{ $ticket->issue}}</div>
                <div>
                    description: {{ $ticket->description}}
                </div>

            </div>
                
                
            
            @endforeach

        </div>
        <div class="bg-gray-200 p-4 h-100">
            <div>Assigned to me</div>
        </div>
        <div class="bg-gray-200 p-4 h-100">
            <div>Closed tickets</div>



            @foreach($tickets_closed as $ticket)
                

                <div class="bg-green-200">
                <div class="flex flex-row justify-between">
                    <div>name: {{ $ticket->name}}</div>
                    <div>date: {{ $ticket->created_at}}</div>
                </div>
                <div>issue: {{ $ticket->issue}}</div>
                <div>
                    description: {{ $ticket->description}}
                </div>

            </div>
                
                
            
            @endforeach
        </div>

    </div>
</x-app-layout>