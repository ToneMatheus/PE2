<x-app-layout>
    <div class="container mx-auto">
        <!-- Date filter -->
        <form action="{{ route('statistics') }}" method="GET" class="my-4">
            <div class="flex items-center space-x-4">
                <label for="start_date" class="text-gray-600">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded px-2 py-1">
                <label for="end_date" class="text-gray-600">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded px-2 py-1">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Apply</button>
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
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/chart-data')
                .then(response => response.json())
                .then(data => {
                    const labels = Object.keys(data);
                    const values = Object.values(data);
                    
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Chart Data',
                                data: values,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        });
    </script>
</x-app-layout>
