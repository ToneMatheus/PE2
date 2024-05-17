<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
</head>
<x-app-layout>
    <div class="p-4 sm:p-8">
        <div class="max-w-xl">
            <h1 class="text-blue-400 text-3xl">Add Payment</h1>
            
            @if (session('success'))
                <div class="bg-green-200 text-green-800 border border-green-600 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="bg-red-200 text-red-800 border border-red-600 px-4 py-2 rounded mb-4">
                    {{ $error }}
                </div>
            @endforeach
            @endif

            <form action="{{ route('payment.add') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="amount" class="block text-gray-700">Amount</label>
                    <input type="text" name="amount" id="amount" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                </div>
                
                <div class="mb-4">
                    <label for="payment_date" class="block text-gray-700">Date of Payment</label>
                    <input type="date" name="payment_date" id="payment_date" class="mt-1 block w-full border border-gray-300 rounded-md" required/>
                </div>
                
                <div class="mb-4">
                    <label for="IBAN" class="block text-gray-700">IBAN</label>
                    <input type="text" name="IBAN" id="IBAN" class="mt-1 block w-full border border-gray-300 rounded-md"/>
                </div>
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md"/>
                </div>
                
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">Address</label>
                    <input type="text" name="address" id="address" class="mt-1 block w-full border border-gray-300 rounded-md" placeholder="Street Number [Box] PostalCode City"/>
                </div>
                
                <div class="mb-4">
                    <label for="structured_communication" class="block text-gray-700">Structured Communication</label>
                    <input type="text" name="structured_communication" id="structured_communication" class="mt-1 block w-full border border-gray-300 rounded-md"/>
                </div>
                
                <button type="submit" class="bg-blue-400 hover:bg-blue-600 text-white font-bold py-1 px-4 rounded">
                    Submit
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

