<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payouts') }}
        </h2>
    </x-slot>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @if(count($creditNotes) > 0)
        <table style="color: white;">
        <tr>
            <th style="color: white;">uID</th>
            <th style="color: white;">Customer</th>
            <th style="color: white;">Invoice ID</th>
            <th style="color: white;">Amount</th>
        </tr>
        @foreach($creditNotes as $creditNote)
            <tr>
                <td>{{$creditNote->uID}}</td>
                <td>{{$creditNote->full_name}}</td>
                <td>{{$creditNote->invoiceID}}</td>
                <td>{{$creditNote->amount}}</td>
                <td><button style="background-color: green;"><a href="{{ route('payouts.pay', ['id' => $creditNote->id]) }}">Pay</a></button></td>
            </tr>
        @endforeach
        </table>
    @else
        <p style="color: white;">No outstanding payments.</p>
    @endif
</x-app-layout>
