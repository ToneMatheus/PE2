<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Invoice Extra Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form id="addInvoiceExtraForm" action="{{ route('addInvoiceExtraForm')}}" method="POST">
                        @csrf
                        <div class="mb-4">
                        <x-input-label for="type" :value="__('Type')" />
                            <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type')" required autofocus />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount')" required autofocus />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="userID" :value="__('User ID')" />
                            <x-text-input id="userID" class="block mt-1 w-full" type="number" name="userID" :value="$userID" required autofocus readonly/>
                        </div>
                        <x-primary-button class="mt-4">
                            {{ __('Add extra invoice line') }}
                        </x-primary-button>
                    </form>

                    <!-- Back button -->
                    <x-secondary-button onclick="history.back()" class="mt-4">
                        {{ __('Back') }}
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>        
</x-app-layout>
