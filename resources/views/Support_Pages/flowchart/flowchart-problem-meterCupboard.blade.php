<div class="mt-4">
    <x-input-label for="CupBoard{{ $meter }}" :value="__('Meter changes happening?')" />
        <div class="flex items-center space-x-8">
            <div class="flex items-center">
                <input type="radio" name="CupBoard{{ $meter }}" id="YesCupBoard{{ $meter }}" value="Yes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='YesCupBoard{{ $meter }}' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Yes</label>
            </div>
            <div class="flex items-center">
                <input type="radio" name="CupBoard{{ $meter }}" id="NoCupBoard{{ $meter }}" value="No" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                <label for='NoCupBoard{{ $meter }}' class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">No</label>
            </div>
        </div>
    <x-input-error class="mt-2" :messages="$errors->get('CupBoard{{ $meter }}')" />
</div>
<div class="mt-4">
    <div id="NoCupBoardText{{ $meter }}" style="display: none;">
        <p>We will have someone come and take a look as soon as possible.</p>
    </div>

    <div id="YesCupBoardText{{ $meter }}" style="display: none;">
        <p>I can't help you with this at the moment. I need to take the problem to someone else.</p>
    </div>
</div>

<script src="{{ asset('js/TicketFlowchart/flowchart_Cupboard.js') }}"></script>


