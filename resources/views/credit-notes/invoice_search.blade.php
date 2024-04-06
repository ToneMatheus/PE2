@extends('layouts.main_layout')

@section('content')

<div class="flex justify-center align-center">
    <form method="post" action="/customer/invoice/search">
        @csrf
        <input type="text" name="invoice_number" placeholder="Enter invoice number" class="p-2">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded self-start mt-4">Search</button>
    </form>
</div>


@endsection 