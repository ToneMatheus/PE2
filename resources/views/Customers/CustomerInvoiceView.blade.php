<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="font-sans flex flex-col min-h-screen text-gray-800">
    @include('Customers.WebPortalNav')
    <div class="flex flex-col items-center w-full">
        <h1 class="text-2xl mt-5">Invoices</h1>
        <div class="w-full p-10">
            <div class="mt-5">
                <h2>Total Amount of Sent Invoices: {{ $sentInvoicesSum }}</h2>
            </div>
            <div class="flex justify-between mt-5">
                <a href="{{ route('customer.invoiceStatus', ['search' => request('search')]) }}">All</a>
                <a href="{{ route('customer.invoiceStatus', ['status' => 'paid', 'search' => request('search')]) }}">Paid</a>
                <a href="{{ route('customer.invoiceStatus', ['status' => 'pending', 'search' => request('search')]) }}">Pending</a>
                <a href="{{ route('customer.invoiceStatus', ['status' => 'sent', 'search' => request('search')]) }}">Sent</a>
            </div>
            <form class="text-right mt-5" action="{{ route('customer.invoiceStatus') }}" method="GET">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="text" name="search" value="{{ old('search') }}" class="rounded-lg border-2 border-gray-300">
                <button type="submit" class="bg-blue-700 text-white rounded-lg px-2 py-1">Search</button>
            </form>
            <table class="w-full mt-5 border-collapse bg-blue-500 text-white">
                <thead>
                    <tr>
                        <th class="p-2 bg-blue-700 text-left">Amount</th>
                        <th class="p-2 bg-blue-700 text-left">Invoice Date</th>
                        <th class="p-2 bg-blue-700 text-left">Due Date</th>
                        <th class="p-2 bg-blue-700 text-left">Status</th>
                        <th class="p-2 bg-blue-700 text-left">Type</th>
                        <th class="p-2 bg-blue-700 text-left">Download</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="p-2">{{ $invoice->total_amount }}</td>
                            <td class="p-2">{{ $invoice->invoice_date }}</td>
                            <td class="p-2">{{ $invoice->due_date }}</td>
                            <td class="p-2" style="background-color: {{ $invoice->status == 'paid' ? 'green' : ($invoice->status == 'pending' ? 'yellow' : ($invoice->status == 'sent' ? 'red' : 'white')) }}">{{ $invoice->status }}</td>
                            <td class="p-2">{{ $invoice->type }}</td>
                            <td class="p-2"><a href="{{ route('invoice.download', ['id' => $invoice->id]) }}" class="bg-blue-700 text-white rounded-lg px-2 py-1">Download as PDF</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $invoices->links() }}
    </div>
    <div class="mt-auto">
        @include('Customers.WebPortalFooter')
    </div>
</body>
</html>