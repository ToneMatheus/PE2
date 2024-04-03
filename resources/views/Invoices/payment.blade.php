<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>

<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-blue-400 text-3xl">Invoice Details</h1>
        <p>Invoice ID: {{ $invoice->id }}</p>
        <table class="border-collapse border border-gray-200">
            <tr class="border-collapse border border-gray-200 bg-gray-300">
                <th class="border-collapse border border-gray-200 px-5">Type</th>
                <th class="border-collapse border border-gray-200 px-5">Unit price</th>
                <th class="border-collapse border border-gray-200 px-5">Amount</th>
            </tr>
    
            @foreach ($invoice->invoice_lines as $row)
            <tr class="border-collapse border border-gray-200">
                <td class="border-collapse border border-gray-200 px-5">{{ $row->type }}</td>
                <td class="border-collapse border border-gray-200 px-5">{{ $row->unit_price }}</td>
                <td class="border-collapse border border-gray-200 px-5">{{ $row->amount }}</td>
            </tr>
            @endforeach
    
            <tr class="border-collapse border border-gray-200 bg-gray-200">
                <td colspan="2" class="border-collapse border border-gray-200 px-5">Total</td>
                <td class="border-collapse border border-gray-200 px-5">{{ $invoice->total_amount }}</td>
            </tr>       
        </table>

        <p>
            Status: {{ $invoice->status }}
        </p>
    
        <form method="POST" action="{{ route('payment.pay', $invoice->id) }}">
            @csrf
            <p><button type="submit" class="bg-blue-400 hover:bg-blue-600 text-white font-bold py-1 px-4 rounded">Pay</button></p>
        </form>

        @if(session('success'))
        <div class="bg-green-200 text-green-800 border border-green-600 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
        @endif
    </div>
</x-app-layout>