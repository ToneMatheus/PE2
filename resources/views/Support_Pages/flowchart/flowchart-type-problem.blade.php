<div class="mt-4">
    <x-input-label for="TypeProblem" :value="__('What is the problem?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="TypeProblem" id="Electricity" value="Electricity" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='Electricity' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Electricity</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="TypeProblem" id="Gas" value="Gas" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='Gas' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Gas</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="TypeProblem" id="Profile" value="Profile" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='Profile' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Profile</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="TypeProblem" id="Other" value="Other" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='Other' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Other</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('TypeProblem')" />
</div>
<div class="mt-4">
    <div id="OtherText" style="display: none;">
        <p class="mt-1 text-m dark:text-gray-100">
            I can't help further, explain it better.</p>
    </div>

</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_type_problem.js') }}"></script>


