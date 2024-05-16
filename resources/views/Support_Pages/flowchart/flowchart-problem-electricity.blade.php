<div class="mt-4">
    <x-input-label for="powerFailure" :value="__('Over the whole house?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="powerFailure" id="YesPowerFailure" value="Yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='YesPowerFailure' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Yes</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="powerFailure" id="NoPowerFailure" value="No" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='NoPowerFailure' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">No</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('powerFailure')" />
</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_electricity.js') }}"></script>


