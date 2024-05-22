<div class="mt-4">
    <x-input-label for="type" :value="__('What is the problem with adres?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="profileAdres" id="addAdres" value="addAdres" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='addAdres' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">add adres</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="profileAdres" id="changeAdres" value="changeAdres" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='changeAdres' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">change Adres</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('profileAdres')" />
</div>

<div class="mt-4">
    <div id="addAdresText" style="display: none;">
        <p>Help add Adres.</p>
    </div>

    <div id="changeAdresText" style="display: none;">
        <p>I cannot help you with this at the moment as this is a task I cannot do. I need to take the problem to someone else.</p>
    </div>
</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_profile_adres.js') }}"></script>


