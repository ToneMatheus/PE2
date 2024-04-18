<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Contracts') }}
        </h2>
    </x-slot> 
<div class="container mx-auto px-4 sm:px-8 w-full p-10 flex-grow">
    <div class="col-span-1 mt-5 border-2 border-gray-800 bg-gray-700 p-3 rounded text-white">
        <table class="w-full mt-5 border-collapse bg-gray-700 text-white">
            <thead>
                <tr>
                    <th class="p-2 bg-gray-800 text-left">Nr</th>
                    <th class="p-2 bg-gray-800 text-left">Product Name</th>
                    <th class="p-2 bg-gray-800 text-left">Description</th>
                    <th class="p-2 bg-gray-800 text-left">Start Date</th>
                    <th class="p-2 bg-gray-800 text-left">Tariff ID</th>
                    <th class="p-2 bg-gray-800 text-left">Price</th>
                    <th class="p-2 bg-gray-800 text-left">Product Type</th>
                    <th class="p-2 bg-gray-800 text-left">Contract Type</th>
                    <th class="p-2 bg-gray-800 text-left">Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                <tr class="bg-gray-600">
                    <td class="p-2">{{ $loop->index + 1 }}</td>
                    <td class="p-2">{{ $contract->product->product_name}}</td>
                    <td class="p-2">{{ $contract->product->description}}</td>
                    <td class="p-2">{{ $contract->start_date }}</td>
                    <td class="p-2">{{ $contract->tarrif_id }}</td>
                    <td class="p-2">{{ $contract->customer_contract->price}}</td>
                    <td class="p-2">{{ $contract->product->type}}</td>
                    <td class="p-2">{{ $contract->customer_contract->type }}</td>
                    <td class="p-2">
                        <a href="/contract_overview/{{ $contract->id }}/download" class="border border-indigo-600 rounded px-2 py-1 text-indigo-600 hover:text-indigo-900">
                            <i class="fa fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('chatbot.chatbot')
</x-app-layout>