@extends('layouts.main_layout')

@section('content')
    <div class="flex justify-center align-center flex-col p-4">
        <a href="{{ URL::previous() }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 self-start rounded">Back</a>    
        <h2 class="font-bold text-lg mt-2">Create Credit Note</h2>
        <form method="POST" action="{{ route('credit-notes.store') }}">
            @csrf
            <div class="flex justify-center align-center flex-col mt-2">
                <label for="type">Type</label>
                <select name="type" id="type">
                    <option value="Refund">Refund credit note</option>
                    <option value="Internal">Internal credit note</option>
                    <option value="Promotional">Promotional credit note</option>
                </select>
            </div>
            <div class="flex justify-center align-center flex-col mt-2">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" required></textarea>
            </div>
            @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="flex justify-center align-center flex-col mt-2">
                <label for="user_id">User</label>
                <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            @error('user_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <!-- Credit Note Lines -->
            <div id="credit_note_lines" class="mt-4">
                <div class="credit-note-line mt-4">
                    <label for="product_0">Product</label>
                    <input type="text" class="form-control" id="product_0" name="products[]">
                    <label for="quantity_0">Quantity</label>
                    <input type="text" class="form-control" id="quantity_0" name="quantities[]">
                    <label for="price_0">Price per Unit</label>
                    <input type="text" class="form-control" id="price_0" name="prices[]">
                </div>
            </div>
            <div class="flex flex-col">
                <button type="button"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded self-start mt-4"
                    onclick="addCreditNoteLine()">Add Line</button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded self-start mt-4">Submit</button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            let lineCount = 1;

            function addCreditNoteLine() {
                const lineHtml = `
                    <div class="credit-note-line mt-4">
                        <label for="product_${lineCount}">Product</label>
                        <input type="text" class="form-control" id="product_${lineCount}" name="products[]">
                        <label for="quantity_${lineCount}">Quantity</label>
                        <input type="text" class="form-control" id="quantity_${lineCount}" name="quantities[]">
                        <label for="price_${lineCount}">Price per Unit</label>
                        <input type="text" class="form-control" id="price_${lineCount}" name="prices[]">
                    </div>
                `;
                document.getElementById('credit_note_lines').insertAdjacentHTML('beforeend', lineHtml);
                lineCount++;
            }
        </script>
    @endpush
@endsection
