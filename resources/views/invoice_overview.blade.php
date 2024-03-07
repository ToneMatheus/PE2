@extends('layouts.app')
@extends('layouts.topbar')

@section('content')

<div class="flex justify-center items-center w-full mt-10">
    <table>
        <tr class="mx-10">
            <th>Invoice</th>
            <th>Created At</th>
            <th>User</th>
            <th>Type</th>
            <th>Price</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Action</th>
        </tr>
        @foreach ($invoices as $invoice)
        <tr class="mx-10">
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->created_at }}</td>
            <td>{{ $invoice->user_id }}</td>
            <td>{{ $invoice->type }}</td>
            <td>${{ $invoice->price }}</td>
            <td>{{ $invoice->status }}</td>
            <td>{{ $invoice->due_date }}</td>
            <td>
                <button><a href="/invoices/{{ $invoice->id }}/mail"><img src="/mail.png" alt="" class="w-6 h-6"></a></button>
                <button><a href="/invoices/{{ $invoice->id }}/download"><img src="/downloads.png" alt="" class="w-6 h-6"></a></button>
            </td>
        </tr>
        @endforeach
    </table>
</div>

@endsection
