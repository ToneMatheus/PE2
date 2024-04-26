<x-app-layout>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-semibold mb-6">Energy Supplier Statistics</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2">Current Income (Paid Invoices)</h2>
                <p class="text-gray-700">Total current income: ${{ $currentIncome }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2">Potential Income (All Invoices)</h2>
                <p class="text-gray-700">Total potential income: ${{ $potentialIncome }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2">Sold Electricity</h2>
                <p class="text-gray-700">Total electricity sold: {{ $electricitySold }} kWh</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-2">Sold Gas</h2>
                <p class="text-gray-700">Total gas sold: {{ $gasSold }} m³</p>
            </div>
        </div>
    </div>

</x-app-layout>