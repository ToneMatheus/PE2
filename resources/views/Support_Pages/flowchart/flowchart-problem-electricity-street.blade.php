<div class="mt-4">
    <x-input-label for="powerFailerStreet" :value="__('Is it over the whole street?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="powerFailerStreet" id="YesStreet" value="Yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='YesStreet' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Yes</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="powerFailerStreet" id="NoStreet" value="No" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='NoStreet' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">No</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('powerFailerStreet')" />
</div>
<div class="mt-4">
    <div id="powerFailerStreetText" style="display: none;">
        <p>We will pass this on to the grid operator.</p>
    </div>

</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_power_failer_street.js') }}"></script>


