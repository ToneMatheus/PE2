<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Guest Estimation Form') }}
        </h2>
    </x-slot>

    <div class="py-8 dark:text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">
                <h1 class="text-3xl mb-6">Energy Estimation Form</h1>

                <form id="energyEstimationForm" action="{{ route('EstimationGuestForm')}}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="nrOccupants" class="block">Number of Occupants:</label>
                        <input type="number" id="nrOccupants" name="nrOccupants" value="1" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrOccupants')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="isHomeAllDay" class="block">Is Home All Day:</label>
                        <select id="isHomeAllDay" name="isHomeAllDay" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="heatWithPower" class="block">Heat house with electricity:</label>
                        <select id="heatWithPower" name="heatWithPower" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="waterWithPower" class="block">Heat water with electricity:</label>
                        <select id="waterWithPower" name="waterWithPower" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="cookWithPower" class="block">Cook with electricity:</label>
                        <select id="cookWithPower" name="cookWithPower" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="nrAirCon" class="block">Number of air conditioning units:</label>
                        <input type="number" id="nrAirCon" name="nrAirCon" value="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrAirCon')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="nrFridges" class="block">Number of fridges and freezers:</label>
                        <input type="number" id="nrFridges" name="nrFridges" value="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrFridges')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="nrWashers" class="block">Number of washers and dryers:</label>
                        <input type="number" id="nrWashers" name="nrWashers" value="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrWashers')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="nrComputers" class="block">Number of computer devices:</label>
                        <input type="number" id="nrComputers" name="nrComputers" value="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrComputers')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="nrEntertainment" class="block">Number of entertainment devices:</label>
                        <input type="number" id="nrEntertainment" name="nrEntertainment" value="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrEntertainment')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                    </div>
                    <div class="mb-4">
                        <label for="nrDishwashers" class="block">Number of dishwashers:</label>
                        <input type="number" id="nrDishwashers" name="nrDishwashers" value="0" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1">
                        @error('nrDishwashers')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Calculate Energy Estimate</button>
                </form>
            </div>
        </div>

        @isset($totalEstimateYear)
            <script>
                window.scrollTo(0, 1000);
            </script>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">
                    <h2 class="text-2xl mb-4">Total Energy Estimate for the Year:</h2>
                    <p>{{ $totalEstimateYear }} kWh</p>
                </div>
            </div>
        @endisset
    </div>
</x-app-layout>
