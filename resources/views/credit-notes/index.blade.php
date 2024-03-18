@extends('layouts.app') {{-- Assuming you have a layout file --}}

@section('content')
    <div class="container">
        <a href="/credit-notes" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Credit Note</a>
        <h1>Credit Notes Overview</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($creditNotes as $creditNote)
                    <tr>
                        <td>{{ $creditNote->type }}</td>
                        <td>{{ $creditNote->description }}</td>
                        <td>{{ $creditNote->amount }}</td>
                        <td>{{ $creditNote->user->username }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection