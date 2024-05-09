<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payouts') }}
        </h2>
    </x-slot>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @if(count($creditNotes) > 0)
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">uID</th>
                    <th scope="col" class="px-6 py-3">Customer</th>
                    <th scope="col" class="px-6 py-3">Invoice ID</th>
                    <th scope="col" class="px-6 py-3">Amount</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($creditNotes as $creditNote)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-4">{{$creditNote->uID}}</td>
                        <td class="px-6 py-4">{{$creditNote->full_name}}</td>
                        <td class="px-6 py-4">{{$creditNote->invoiceID}}</td>
                        <td class="px-6 py-4">{{$creditNote->amount}}</td>
                        <td class="px-6 py-4"><button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"><a href="{{ route('payouts.pay', ['id' => $creditNote->id]) }}">Pay</a></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: white;">No outstanding payments.</p>
    @endif
</x-app-layout>
