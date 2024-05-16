<div class="mt-4">
    <x-input-label for="mkContract" :value="__('Do you want a contract?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="mkContract" id="YesMkContract" value="Yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='YesMkContract' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Yes</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="mkContract" id="NoMkContract" value="No" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='NoMkContract' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">No</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('mkContract')" />
</div>
<div class="mt-4">
    <div id="makeContractYes" style="display: none;">
    <p>Make a contract.</p>
    </div>

    <div id="makeContractNo" style="display: none;">
        <p>I can't help further, explain it better.</p>
    </div>

</div>


<script src="{{ asset('js/TicketFlowchart/flowchart_mk_contract.js') }}"></script>