
<x-app-layout>
    <div class="flex flex-col items-center w-full text-white">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Invoices') }}
        </h2>
    </x-slot>    
        <div class="w-full p-10 flex-grow">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1 mt-5 border-2 border-gray-800 bg-gray-700 p-3 rounded text-white">
                    @if ($sentInvoicesSum > 0)
                        <h2>{{ __('messages.Total Amount to be paid') }}: {{ $sentInvoicesSum }}</h2>
                    @else
                        <h2>{{ __('messages.No Invoices') }}</h2>
                    @endif
                </div>
                <div class="col-span-1"></div>
                <form method="GET" action="{{ route('customer.invoiceStatus') }}" class="col-span-1 mt-5 border-2 border-gray-800 bg-gray-600 p-3 rounded text-white">
                    <select name="address" onchange="this.form.submit()" class="w-full bg-gray-700 text-white border border-gray-800 rounded py-2 px-4">
                        <option value="">{{ __('messages.show_all_addresses') }}</option>
                        @foreach($addresses as $address)
                            <option value="{{ $address->address }}" {{ request('address') == $address->address ? 'selected' : '' }}>
                                {{ $address->address }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex justify-between mt-5 items-center">
                <div class="flex space-x-4">
                    <a href="{{ route('customer.invoiceStatus', ['search' => request('search')]) }}" class="{{ request('status') == null ? 'bg-gray-600' : 'bg-gray-800' }} text-white px-3 py-2 rounded w-30 text-center">{{ __('messages.all') }}</a>
                    <a href="{{ route('customer.invoiceStatus', ['status' => 'paid', 'search' => request('search')]) }}" class="{{ request('status') == 'paid' ? 'bg-gray-600' : 'bg-gray-800' }} text-white px-3 py-2 rounded w-30 text-center">{{ __('messages.paid') }}</a>
                    <a href="{{ route('customer.invoiceStatus', ['status' => 'pending', 'search' => request('search')]) }}" class="{{ request('status') == 'pending' ? 'bg-gray-600' : 'bg-gray-800' }} text-white px-3 py-2 rounded w-30 text-center">{{ __('messages.pending') }}</a>
                    <a href="{{ route('customer.invoiceStatus', ['status' => 'sent', 'search' => request('search')]) }}" class="{{ request('status') == 'sent' ? 'bg-gray-600' : 'bg-gray-800' }} text-white px-3 py-2 rounded w-30 text-center">{{ __('messages.sent') }}</a>
                </div>
                <form class="text-right" action="{{ route('customer.invoiceStatus') }}" method="GET">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ old('search') }}" class="rounded-lg border-2 border-gray-800 bg-gray-600 text-white">
                    <button type="submit" class="bg-gray-800 text-white rounded-lg px-2 py-1">Search</button>
                </form>
            </div>
            <table class="w-full mt-5 border-collapse bg-gray-700 text-white">
                <thead>
                    <tr>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Amount') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Invoice Date') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Due Date') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Status') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Type') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Address') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Download') }}</th>
                        <th class="p-2 bg-gray-800 text-left">{{ __('messages.Pay') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr class="bg-gray-600">
                            <td class="p-2">{{ $invoice->total_amount }}</td>
                            <td class="p-2">{{ $invoice->invoice_date }}</td>
                            <td class="p-2">{{ $invoice->due_date }}</td>
                            <td class="p-2">{{ __('messages.' . $invoice->status) }}</td>
                            <td class="p-2">{{ __('messages.' . $invoice->type) }}</td>
                            <td class="p-2">{{ $invoice->address }}</td>
                            <td class="p-2"><a href="{{ route('invoice.download', ['id' => $invoice->id]) }}" class="bg-gray-800 text-white rounded-lg px-2 py-1">{{ __('messages.download') }}</a></td>
                            <td class="p-2"><a href="{{ route('payment.show', ['id' => $invoice->id, 'hash' => $invoice->hash]) }}" class="bg-gray-800 text-white rounded-lg px-2 py-1">{{ __('messages.Pay') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($invoices->hasPages())
            <nav class="flex items-center justify-between">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm dark:text-white leading-5">
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
        @include('chatbot.chatbot')
</x-app-layout>
