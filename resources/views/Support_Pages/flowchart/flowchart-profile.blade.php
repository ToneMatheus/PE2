<div class="mt-4">
    <x-input-label for="type" :value="__('Have profile problem?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="profile" id="Adres" value="Adres" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='Adres' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Adres</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="profile" id="ProfileInfo" value="ProfileInfo" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='ProfileInfo' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Profile info</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('profile')" />
</div>
<div class="mt-4">
    <div id="ProfileInfoText" style="display: none;">
        <p>Help change profile.</p>
    </div>
</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_profile.js') }}"></script>


