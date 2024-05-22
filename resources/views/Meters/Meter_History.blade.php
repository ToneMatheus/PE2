<x-app-layout title="Meter history">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Hello, {{$details[0]->first_name}}
        </h2>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto dark:text-white grid grid-cols-2 gap-4">
        <div class="sm:px-6 lg:px-8 space-y-6">
            <h1 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">Meter History</h1>
            <form method="POST" action="{{ route('submitIndexCustomer') }}">
                @csrf
                @method('POST')
                <div  class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Your meters</h2>
                <x-primary-button type="submit" class="w-auto dark:bg-red-700 dark:text-white" id="submit" :disabled="true">
                    {{ __('Submit values') }}
                </x-primary-button>
                </div>
                @foreach ($details as $detail)
                    <div class="p-4 my-3 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg text-gray-500 dark:text-gray-400 meter">
                        <p>EAN code: <span class="text-gray-800 dark:text-white font-semibold">{{$detail->EAN}}</span></p>
                        <p>Type: <span class="text-gray-800 dark:text-white font-semibold">{{$detail->type}}</span></p>
                        <p>Address: <span class="text-gray-800 dark:text-white font-semibold">{{$detail->street}} {{$detail->number}}, {{$detail->postal_code}} {{$detail->city}}</span></p>
                        <p>Meter ID: {{$detail->meter_id}}</p>
                        <div class="flex justify-between my-4">
                        <p>Last read on: <span class="text-gray-800 dark:text-white font-semibold">{{$detail->reading_date ? $detail->reading_date : 'Not read yet'}}</span></p>
                        <p>Latest reading value: <span class="text-gray-800 dark:text-white font-semibold" id="latest-{{$detail->meter_id}}">{{$detail->latest_reading_value ? $detail->latest_reading_value : 'Not read yet'}}</span></p>
                        </div>
                        <input type="hidden" name="index_values[{{$loop->index}}][user_id]" value="{{$detail->user_id}}"/>
                        <input type="hidden" name="index_values[{{$loop->index}}][EAN]" value="{{$detail->EAN}}"/>
                        <input type="hidden" name="index_values[{{$loop->index}}][meter_id]" value="{{$detail->meter_id}}"/>
                        @if ($detail->expecting_reading == 1)
                            <x-text-input class="block mt-1 w-full indexValue" type="text" name="index_values[{{$loop->index}}][new_index_value]" id="{{$detail->meter_id}}" required placeholder="Enter index value" autocomplete="off"/>
                            <div id="validation-{{$detail->meter_id}}" class="mt-5"></div>
                            <p class="float-right">Consumption: <span id="consumption-{{$detail->meter_id}}"></span></p>
                        @else
                            <div class="p-2 w-full bg-rose-200 dark:bg-rose-300 rounded-lg flex unneeded">
                                <p class="ml-4 text-red-700">Not today!</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </form>
        </div>

        <div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            @foreach($index_values as $index_value)
                <div class="mb-3 bg-white dark:bg-gray-800 shadow rounded-lg p-7">
                    <canvas id="consumptionChart{{ $index_value[0]->meter_id }}"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            function indexValidate(meterID, indexValue){
                var field = "#validation-" + meterID;
                var consumption = "#consumption-" + meterID;
                var latest = $("#latest-" + meterID).html();
                var validation = document.querySelector(field);
                $.ajax({
                    url:"{{ route('ValidateIndex') }}",
                    method:'GET',
                    data:{meterID:meterID, indexValue:indexValue},
                    success:function(data)
                    {
                        $(validation).html(data);
                        consumptionCalculator(indexValue, consumption, latest);
                        enableButton();
                    }
                })
            }

            function consumptionCalculator(indexValue, consumption, latest) {
                if (latest == 'Not read yet') {
                    latestValue = 0;
                }
                else {
                    latestValue = parseInt(latest);
                }

                consumptionValue = indexValue - latestValue;

                if (indexValue - latestValue > 0) {
                    $(consumption).html(consumptionValue);
                }
                else{
                    $(consumption).html('');
                }
            }

            function enableButton() {
                if(document.getElementsByClassName("correct").length == document.getElementsByClassName("meter").length - document.getElementsByClassName("unneeded").length) {
                    $('#submit').prop('disabled', false);
                }
                else {
                    $('#submit').prop('disabled', true);
                }
            }

            $(document).on('keyup', '.indexValue', function(){
                indexValidate(this.id, this.value);
            })

            var index_values = @json($index_values);

            function createChart(index_value) {
                var ctx = document.getElementById('consumptionChart' + index_value[0].meter_id).getContext('2d');
                var labels = [];
                var individual_index_values = [];
                var consumption_values = [];
                var title = "Index value and consumption for meter " + index_value[0].EAN;

                labels = index_value.map(item => item.reading_date);
                individual_index_values = index_value.map(item => item.reading_value);

                for (let i = 0; i < individual_index_values.length; i++) {
                    if (i === 0) {
                        consumption_values.push(0);
                    } else {
                        consumption_values.push(individual_index_values[i] - individual_index_values[i - 1]);
                    }
                }

                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Index Values',
                            type: 'line',
                            data: individual_index_values,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: 'Consumption Values',
                            type: 'bar',
                            data: consumption_values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: title,
                                font: {
                                    size: 14
                                },
                                padding: {
                                    top: 10,
                                    bottom: 30
                                },
                                color: '#111'
                            }
                        }
                        
                    }
                });
            }

            index_values.forEach(index_value => {
                createChart(index_value);
            });
        })
    </script>
    </x-app-layout>


