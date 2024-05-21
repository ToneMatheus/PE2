<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manual Invoice') }}
        </h2>

        <style>
            #container {
                display: none;
            }
        </style>
    </x-slot>

    <select id="customerSelect" name="customerSelect" class="w-full">
        <option value="">Select a Customer</option>
        @foreach ($customers as $customer)
            <option value="{{ $customer->user_id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
        @endforeach
    </select>

    <input type="hidden" id="metersData" name="metersData" value="{{ json_encode($meters) }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="mt-4" id="container">
        <form method="post" action="{{ route('manualInvoice.process') }}">
            @csrf
            <select name="meter" id="customerMetersContainer">
            </select>

            <div class="mt-4">
                <input type="submit" name="action" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900" value="monthly">
                <input type="submit" name="action" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900" value="annual">
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#customerSelect').select2({
                placeholder: "Search customer",
                allowClear: true,
                width: '100%'
            });

            $('#customerSelect').on('change', function() {
                $('#container').show();

                var customerId = $(this).val();

                if (customerId) {
                    var metersData = JSON.parse($('#metersData').val());
                    console.log("Meters data:", metersData);

                    var customerMeters = metersData.filter(function(meter) {
                        return meter.user_id == customerId;
                    });
                    console.log("Customer meters:", customerMeters);

                    var metersHtml = '';
                    customerMeters.forEach(function(meter) {
                        metersHtml += '<option value="'+ meter.id +'">' + meter.street + ' ' + meter.number + ' ' +  meter.postal_code;
                        metersHtml += '</option>';
                    });
                    console.log("Meters HTML:", metersHtml);

                    $('#customerMetersContainer').html(metersHtml);
                } else {
                    $('#customerMetersContainer').empty();
                }
            });
        });
    </script>
</x-app-layout>
