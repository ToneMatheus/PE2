<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('Support_Pages.flowchart.flowchart-contract')
            </div>
        </div>

        <div id="makeContractContainer" style="display: none;" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            @include('Support_Pages.flowchart.flowchart-make-contract')
        </div>

        <div id="typeProblemContainer" style="display: none;">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('Support_Pages.flowchart.flowchart-type-problem')
            </div>

            <div style="margin-top: 1.5em;"></div>

            <div id="electricityContainer" style="display: none;">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('Support_Pages.flowchart.flowchart-problem-electricity')
                </div>

                <div style="margin-top: 1.5em;"></div>

                <div id="nonBlownFuseContainer" style="display: none;" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('Support_Pages.flowchart.flowchart-problem-electricity-fuse')
                </div>
                    
                <div id="powerFailureStreetContainer" style="display: none;" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('Support_Pages.flowchart.flowchart-problem-electricity-street')
                </div>

                <div style="margin-top: 1.5em;"></div>

                <div id="meterCupboardContainer" style="display: none;" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('Support_Pages.flowchart.flowchart-problem-meterCupboard', ['meter' => "electricity"])
                </div>
            </div>

            <!-- LOOK -->
            <div id="profileContainer" style="display: none;">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('Support_Pages.flowchart.flowchart-profile')
                </div>

                <div style="margin-top: 1.5em;"></div>

                <div  id="profileProblemAdress" style="display: none;"  class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('Support_Pages.flowchart.flowchart-profile-adres')
                </div>
            </div>

            <div id="gasContainer" style="display: none;" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('Support_Pages.flowchart.flowchart-problem-meterCupboard', ['meter' => "gas"])
            </div>

        </div>
    </div>
</div>
</x-app-layout>
