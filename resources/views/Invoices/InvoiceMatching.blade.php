<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Matching</title>
</head>

<x-app-layout>
    <div class="p-4 sm:p-8">
    <div class="max-w-screen-xl mx-auto">
        <h1 class="text-blue-400 text-3xl">Invoice Matching Overview</h1>
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <tr class="border-collapse border border-gray-200 bg-gray-300">
                <th class="border-collapse border border-gray-200 px-5">Payment ID</th>
                <th class="border-collapse border border-gray-200 px-5">Date of Payment</th>
                <th class="border-collapse border border-gray-200 px-5">Name</th>
                <th class="border-collapse border border-gray-200 px-5">IBAN</th>
                <th class="border-collapse border border-gray-200 px-5">Matched</th>
                <th class="border-collapse border border-gray-200 px-5">Matched Invoice ID</th>
            </tr>
    
            @foreach ($payments as $row)
            <tr class="border-collapse border border-gray-200">
                <td class="border-collapse border border-gray-200 px-5">{{ $row->id }}</td>
                <td class="border-collapse border border-gray-200 px-5">{{ $row->payment_date }}</td>
                <td class="border-collapse border border-gray-200 px-5">{{ $row->name }}</td>
                <td class="border-collapse border border-gray-200 px-5">{{ $row->IBAN }}</td>
                @if ($row->has_matched == 1)
                    <td class="border-collapse border border-gray-200 px-5 bg-green-200 text-green-800">matched</td>
                    <td class="border-collapse border border-gray-200 px-5">{{ $row->invoice_id }}</td>
                @else
                    <td class="border-collapse border border-gray-200 px-5 bg-red-200 text-red-800">not matched</td>
                    <td class="border-collapse border border-gray-200 px-5">N/A</td>
                @endif
            </tr>
            @endforeach     
        </table>
    </div>
    </div>
</x-app-layout>