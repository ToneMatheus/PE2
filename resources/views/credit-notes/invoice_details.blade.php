@extends('layouts.main_layout')

@section('content')

<div class="p-4 h-screen">
    <h1 class="font-bold text-lg">Invoice Details: INV{{ $invoiceId }}</h1>
    <form method="post" action="/refund">
        @csrf
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
        
        @foreach ($invoice->invoice_lines as $item)
            <div class="flex justify-between items-center border-b border-slate-400 py-4 w-1/2">
                <p>{{ $item->type }}</p>
                <div class="flex justify-center items-center gap-4">
                    <p class="text-center">&euro; {{ $item->amount*$item->unit_price }}</p>
                    <label>
                        <input type="checkbox" name="line_items[]" value="{{ $item->id }}">
                    </label>
                </div>
            </div>
        @endforeach
    
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Refund</button>
    </form>
</div>

@endsection