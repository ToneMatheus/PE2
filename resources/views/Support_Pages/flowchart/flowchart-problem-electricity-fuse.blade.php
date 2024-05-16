<div class="mt-4">
    <x-input-label for="blownFuse" :value="__('Blown fuse?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="blownFuse" id="YesBlownFuse" value="Yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='YesBlownFuse' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Yes</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="blownFuse" id="NoBlownFuse" value="No" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='NoBlownFuse' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">No</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('blownFuse')" />
</div>
<div class="mt-4">
    <div id="BlownFuseText" style="display: none;">
        <p>Turn the fuse back on. If the fuse blows immediately, you must unplug all devices in the circuit and plug them back in one by one.
            Because the problem is probably that there is a short circuit on the circuit. If you prefer that a technician come immediately, 
            I will schedule one for you. Otherwise you can take everything off first. And if the problem still persists, you must schedule a technician 
            or call back so that we can schedule a technician.</p>
    </div>

</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_blown_fuse.js') }}"></script>


