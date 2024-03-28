<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold">{{$job}} History</h1>
        <button onclick="closeHistoryModal()" class="px-4 py-2 text-black-500 focus:outline-none mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <hr/>
    </div>
    @if (empty($groupedHistories))
        <p class="text-gray-600">No history available.</p>
    @else
        <div class="overflow-x-auto">
            @php $groupCount = 1; @endphp
            @foreach ($groupedHistories as $group)
                <div class="mb-4">
                    <table class="table-auto w-full">
                        @if ($loop->first)
                            <thead>
                                <tr>
                                    <th class="px-2 py-2">Status</th>
                                    <th class="px-2 py-2">Date</th>
                                </tr>
                            </thead>
                        @endif
                        <tbody>
                            @foreach ($group as $entry)
                                <tr>
                                    <td class="border px-2 py-2">{{ $entry->status }}</td>
                                    <td class="border px-2 py-2">{{ $entry->completed_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @php $groupCount++; @endphp
            @endforeach
        </div>
        <div class="mt-4 flex justify-between items-center">
            <button onclick="showHistoryPage('{{$job}}', {{$currentPage-1}})" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 focus:outline-none">Previous</button>
            <button onclick="showHistoryPage('{{$job}}', {{$currentPage+1}})" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 focus:outline-none"
                {{ $currentPage == $totalPages ? 'disabled' : '' }}>
                Next
            </button>
            <span class="text-gray-600">Page {{$currentPage}} of {{$totalPages}}</span>
        </div>
    @endif
</div>
