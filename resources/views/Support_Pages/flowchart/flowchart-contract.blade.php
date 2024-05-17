<div class="mt-4">
    <x-input-label for="type" :value="__('Have a contract?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="contract" id="Yes" value="Yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='Yes' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Yes</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="contract" id="No" value="No" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='No' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">No</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('contract')" />
</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_contract.js') }}"></script>


