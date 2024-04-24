@extends('layouts.main_layout')

@section('content')
<div class="container mx-auto px-4 py-8 flex">
    <div class="w-1/4 pr-4">
        <div class = "bg-white p-4 rounded shadow-md">
        <form action="{{ route('invoices.show') }}" method="GET">
            <h2 class="text-lg font-semibold mb-4">Filters</h2>
            <label for="year" class="block text-gray-700 mb-2">Time range:</label>
            <select id="year" name="year" class="form-select w-full mb-4">
                <option value="">Select Year</option>
                <option value="last3Months" {{ $selectedYear === 'last3Months' ? 'selected' : '' }}>Last 3 Months</option>
                <option value="last6Months" {{ $selectedYear === 'last6Months' ? 'selected' : '' }}>Last 6 Months</option>
                @foreach ($filterYears as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            
            <label for="status" class="block text-gray-700 mb-2">Payment status:</label>
            <select id="status" name="status" class="form-select w-full mb-4">
                <option value="" {{ $selectedStatus === '' ? 'selected' : '' }}>All</option>
                <option value="paid" {{ $selectedStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ $selectedStatus === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <!-- Add more options if needed -->
            </select>
            
            <button type="submit" id="apply_filters" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">Apply Filters</button>
        </form>
        </div>
    </div>
    <div class="w-3/4">
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200 border border-gray-400">ID</th>
                        <th class="px-4 py-2 bg-gray-200 border border-gray-400 cursor-pointer" onclick="sortTable('InvoiceDate')">Invoice Date <i class="fas fa-sort"></i></th>
                        <th class="px-4 py-2 bg-gray-200 border border-gray-400 cursor-pointer" onclick="sortTable('DueDate')">Due Date <i class="fas fa-sort"></i></th>
                        <th class="px-4 py-2 bg-gray-200 border border-gray-400">Amount</th>
                        <th class="px-4 py-2 bg-gray-200 border border-gray-400">Action</th>
                        <th class="px-4 py-2 bg-gray-200 border border-gray-400 cursor-pointer" onclick="sortTable('Status')">Payment Status <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($invoices) == 0)
                        <tr>
                            <td>
                                Could not find any invoices.
                            </td>
                        </tr>
                    @else
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="px-4 py-2 border border-gray-400">{{ $invoice->id }}</td>
                                <td name="InvoiceDate" class="px-4 py-2 border border-gray-400">{{ $invoice->invoice_date }}</td>
                                <td name="DueDate" class="px-4 py-2 border border-gray-400">{{ $invoice->due_date }}</td>
                                <td class="px-4 py-2 border border-gray-400">{{ $invoice->total_amount }} €</td>
                                <td class="px-4 py-2 border border-gray-400">
                                    <a href="" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">View</a>
                                    <!-- Add other actions like edit or delete if needed -->
                                </td>
                                <td name="Status" class="px-4 py-2 border border-gray-400">{{ $invoice->status }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection