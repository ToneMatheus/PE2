<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.Customer Invoices') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="font-sans flex flex-col min-h-screen text-gray-800">
    @include('Customers.WebPortalNav')
    <div class="flex flex-col items-center w-full">
        <h1 class="text-2xl mt-5">{{ __('messages.Invoices') }}</h1>
        <div class="w-full p-10">
            <div class="mt-5 border-2 border-blue-700 bg-blue-100 p-3 rounded">
                @if ($sentInvoicesSum > 0)
                    <h2>{{ __('messages.Total Amount to be paid') }}: {{ $sentInvoicesSum }}</h2>
                @else
                    <h2>{{ __('messages.No Invoices') }}</h2>
                @endif
            </div>
            <div class="flex justify-between mt-5 items-center">
                <div class="flex space-x-4">
                    <a href="{{ route('customer.invoiceStatus', ['search' => request('search')]) }}" class="bg-blue-700 text-white px-3 py-2 rounded">{{ __('messages.all') }}</a>
                    <a href="{{ route('customer.invoiceStatus', ['status' => 'paid', 'search' => request('search')]) }}" class="bg-blue-700 text-white px-3 py-2 rounded">{{ __('messages.paid') }}</a>
                    <a href="{{ route('customer.invoiceStatus', ['status' => 'pending', 'search' => request('search')]) }}" class="bg-blue-700 text-white px-3 py-2 rounded">{{ __('messages.pending') }}</a>
                    <a href="{{ route('customer.invoiceStatus', ['status' => 'sent', 'search' => request('search')]) }}" class="bg-blue-700 text-white px-3 py-2 rounded">{{ __('messages.sent') }}</a>
                </div>
                <form class="text-right" action="{{ route('customer.invoiceStatus') }}" method="GET">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ old('search') }}" class="rounded-lg border-2 border-gray-300">
                    <button type="submit" class="bg-blue-700 text-white rounded-lg px-2 py-1">Search</button>
                </form>
            </div>
            <table class="w-full mt-5 border-collapse bg-blue-500 text-white">
                <thead>
                    <tr>
                        <th class="p-2 bg-blue-700 text-left">{{ __('messages.Amount') }}</th>
                        <th class="p-2 bg-blue-700 text-left">{{ __('messages.Invoice Date') }}</th>
                        <th class="p-2 bg-blue-700 text-left">{{ __('messages.Due Date') }}</th>
                        <th class="p-2 bg-blue-700 text-left">{{ __('messages.Status') }}</th>
                        <th class="p-2 bg-blue-700 text-left">{{ __('messages.Type') }}</th>
                        <th class="p-2 bg-blue-700 text-left">{{ __('messages.Download') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="p-2">{{ $invoice->total_amount }}</td>
                            <td class="p-2">{{ $invoice->invoice_date }}</td>
                            <td class="p-2">{{ $invoice->due_date }}</td>
                            <td class="p-2" style="background-color: {{ $invoice->status == 'paid' ? 'green' : ($invoice->status == 'pending' ? 'yellow' : ($invoice->status == 'sent' ? 'red' : 'white')) }}">{{ __('messages.' . $invoice->status) }}</td>
                            <td class="p-2">{{ __('messages.' . $invoice->type) }}</td>
                            <td class="p-2"><a href="{{ route('invoice.download', ['id' => $invoice->id]) }}" class="bg-blue-700 text-white rounded-lg px-2 py-1">{{ __('messages.download') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($invoices->hasPages())
            <nav class="flex items-center justify-between">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 leading-5">
                            {!! __('pagination.Showing') !!}
                            <span class="font-medium">{{ $invoices->firstItem() }}</span>
                            {!! __('pagination.to') !!}
                            <span class="font-medium">{{ $invoices->lastItem() }}</span>
                            {!! __('pagination.of') !!}
                            <span class="font-medium">{{ $invoices->total() }}</span>
                            {!! __('pagination.results') !!}
                        </p>
                    </div>

                    <div>
                        <span class="relative z-0 inline-flex shadow-sm ml-2">
                            @if ($invoices->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">
                                    &laquo; {!! __('pagination.Previous') !!}
                                </span>
                            @else
                                <a href="{{ $invoices->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-700">
                                    &laquo; {!! __('pagination.Previous') !!}
                                </a>
                            @endif

                            @foreach (range(1, $invoices->lastPage()) as $i)
                                @if ($i == $invoices->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $i }}</span>
                                @else
                                    <a href="{{ $invoices->url($i) }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-700">{{ $i }}</a>
                                @endif
                            @endforeach

                            @if ($invoices->hasMorePages())
                                <a href="{{ $invoices->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 hover:text-gray-700">
                                    {!! __('pagination.Next') !!} &raquo;
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">
                                    {!! __('pagination.Next') !!} &raquo;
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </nav>
        @endif
    <div class="inset-x-0 bottom-0 w-full mt-5">
        @include('Customers.WebPortalFooter')
    </div>
</body>
</html>