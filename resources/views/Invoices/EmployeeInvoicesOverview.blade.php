<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All invoices') }}
        </h2>
    </x-slot>
    <div class="bg-gray-900 dark:bg-gray-900 p-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="{{ route('invoices.show') }}" method="GET">
                        <h2 class="text-lg font-semibold text-white dark:text-gray-200 mb-4">Filters</h2>
                        <label for="year" class="block text-white dark:text-gray-200 mb-2">Time range:</label>
                        <select id="year" name="year" class="form-select w-full mb-4 bg-gray-800 dark:bg-gray-700 text-white dark:text-gray-200">
                            <option value="">Select Time</option>
                            @foreach ($filterYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        
                        <label for="status" class="block text-white dark:text-gray-200 mb-2">Invoice status:</label>
                        <select id="status" name="status" class="form-select w-full mb-4 bg-gray-800 dark:bg-gray-700 text-white dark:text-gray-200">
                            <option value="" {{ $selectedStatus === '' ? 'selected' : '' }}>All</option>
                            <option value="paid" {{ $selectedStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ $selectedStatus === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="sent" {{ $selectedStatus === 'sent' ? 'selected' : '' }}>Sent</option>
                        </select>
                        
                        <button type="submit" id="apply_filters" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Apply Filters</button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                <table class="table-auto  w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Invoice ID</th>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Meter ID</th>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Customer</th>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Invoice Date</th>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Due Date</th>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Invoice Type</th>
                            <th class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200">Invoice Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($invoices) == 0)
                            <tr>
                                <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200" colspan="4">Could not find any invoices.</td>
                            </tr>
                        @else
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->id }}</td>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->meter_id }}</td>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->first_name }} {{ $invoice->last_name }}</td>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->invoice_date }}</td>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->due_date }}</td>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->type }}</td>
                                    <td class="px-4 py-2 bg-gray-800 dark:bg-gray-800 text-white dark:text-gray-200 text-center">{{ $invoice->status }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
    </div>
</x-app-layout>
