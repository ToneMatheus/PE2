<x-app-layout>
    <div class="container mx-auto">
        <!-- Date filter -->
        <form action="{{ route('statistics') }}" method="GET" class="my-4">
            <div class="flex items-center space-x-4">
                <label for="start_date" class="text-gray-600">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="border border-gray-300 rounded px-2 py-1">
                <label for="end_date" class="text-gray-600">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="border border-gray-300 rounded px-2 py-1">
                <button id="button" type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Apply</button>
            </div>
        </form>

        <!-- Overview data -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <p class="text-lg font-semibold">Energy Statistics</p>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <p class="text-gray-600">Gross Income:</p>
                    <p class="text-xl font-semibold">${{ $totalGrossIncome }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Potential Gross Income:</p>
                    <p class="text-xl font-semibold">${{ $totalPotentialGrossIncome }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Amount Due:</p>
                    <p class="text-xl font-semibold">${{ $amountDue }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Paid Invoices %:</p>
                    <p class="text-xl font-semibold">{{ $ratioPaidUnpaid }} %</p>
                </div>
                <div>
                    <p class="text-gray-600">Total Sold Electricity:</p>
                    <p class="text-xl font-semibold">{{ $totalSoldElectricity }} kWh</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 mt-8">
            {!! $chart->container() !!}
        </div>
        {{-- Credit score container --}}
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-6">Customer scores</h1>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="w-1/4 text-left py-2 px-4">Username</th>
                            <th class="w-1/4 text-left py-2 px-4">Name</th>
                            <th class="w-1/4 text-left py-2 px-4">Email</th>
                            <th class="w-1/4 text-left py-2 px-4">Nationality</th>
                            <th class="w-1/4 text-left py-2 px-4">Credit Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userCreditScores as $userCredit)
                            <tr class="{{ $loop->even ? 'bg-gray-100' : '' }}">
                                <td class="py-2 px-4">{{ $userCredit['user']->username }}</td>
                                <td class="py-2 px-4">{{ $userCredit['user']->first_name }} {{ $userCredit['user']->last_name }}</td>
                                <td class="py-2 px-4">{{ $userCredit['user']->email }}</td>
                                <td class="py-2 px-4">{{ $userCredit['user']->nationality }}</td>
                                <td class="py-2 px-4">{{ $userCredit['credit_score'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        function formatYAxisDecimal() {
            // Select all elements with the class 'apexcharts-yaxis'
            var yAxisElements = document.querySelectorAll('.apexcharts-yaxis-label');

            // Iterate over each element and modify its inner HTML
            yAxisElements.forEach(function(element) {
                // Get the original text content
                var originalText = element.textContent;

                // Convert the text content to a floating point number with 2 decimal points
                var formattedText = parseFloat(originalText).toFixed(2);

                // Set the formatted text as the new content
                element.textContent = formattedText;
            });
        }

        // Call the function when the document is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            formatYAxisDecimal();
        });
    </script>
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
</x-app-layout>
