{{-- <!DOCTYPE html>
<html>
<head>
    <title>Meter History</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
</head>
<body> --}}
<x-app-layout title="Meter history">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    {{-- <div class="modal fade" id="indexModal" tabindex="-1" aria-labelledby="indexModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indexModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form method="POST" action="{{ route('submitIndexCustomer') }}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            @csrf
                            @method('POST')
                            <p>Previous index value = <span id="prev"></span></p>
                            <input id="meter_id" name="meter_id" type="hidden">
                            <input id="EAN" name="EAN" type="hidden">
                            <label for="index_value">Enter index value for meter <span id="modalEAN" class="modalEAN"></span></label>
                            <input id="index_value" name="index_value" type="text" required class="name form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="enter">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
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
                        <p>EAN code: <span class="text-white font-semibold">{{$detail->EAN}}</span></p>
                        <p>Type: <span class="text-white font-semibold">{{$detail->type}}</span></p>
                        <p>Address: <span class="text-white font-semibold">{{$detail->street}} {{$detail->number}}, {{$detail->postal_code}} {{$detail->city}}</span></p>
                        <p>Meter ID: {{$detail->meter_id}}</p>
                        <div class="flex justify-between my-4">
                        <p>Last read on: <span class="text-white font-semibold">{{$detail->reading_date ? $detail->reading_date : 'Not read yet'}}</span></p>
                        <p>Latest reading value: <span class="text-white font-semibold">{{$detail->latest_reading_value ? $detail->latest_reading_value : 'Not read yet'}}</span></p>
                        </div>
                        <input type="hidden" name="index_values[{{$loop->index}}][user_id]" value="{{$detail->user_id}}"/>
                        <input type="hidden" name="index_values[{{$loop->index}}][EAN]" value="{{$detail->EAN}}"/>
                        <input type="hidden" name="index_values[{{$loop->index}}][meter_id]" value="{{$detail->meter_id}}"/>
                        @if ($detail->expecting_reading == 1)
                            <x-text-input class="block mt-1 w-full indexValue" type="text" name="index_values[{{$loop->index}}][new_index_value]" id="{{$detail->meter_id}}" required placeholder="Enter index value" autocomplete="off"/>
                        @else
                            <div class="p-2 w-full bg-rose-200 dark:bg-rose-300 rounded-lg flex unneeded">
                                <p class="ml-4 text-red-700">Not today!</p>
                            </div>
                        @endif
                        <div id="validation-{{$detail->meter_id}}" class="mt-5"></div>
                    </div>
                @endforeach
            </form>
        </div>
        
        <div>
            <p>chart</p>
        </div> 
    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            function indexValidate(meterID, indexValue){
                var field = "#validation-" + meterID;
                var validation = document.querySelector(field);
                $.ajax({
                    url:"{{ route('ValidateIndex') }}",
                    method:'GET',
                    data:{meterID:meterID, indexValue:indexValue},
                    success:function(data)
                    {
                        $(validation).html(data);
                        enableButton();
                    }
                })
            }

            function enableButton() {
                if(document.getElementsByClassName("correct").length == document.getElementsByClassName("meter").length - document.getElementsByClassName("unneeded").length) {
                    $('#submit').prop('disabled', false);
                }
                else {
                    $('#submit').prop('disabled', true);
                }
                console.log(document.getElementsByClassName("correct").length);
                console.log(document.getElementsByClassName("meter").length);
            }

            $(document).on('keyup', '.indexValue', function(){
                indexValidate(this.id, this.value);
            })
        })
    </script>
        {{-- <div class="content">
            <h1>Energy Consumption History</h1>
            <canvas id="consumptionChart"></canvas>
        </div>
        <script>
            var consumptionData = @json($consumptionData);
        </script>
        <script src="/js/consumptionChart.js"></script>
        <button onclick="fetchData('week')">Week</button>
        <button onclick="fetchData('month')">Month</button>
        <button onclick="fetchData('year')">Year</button> --}}
    {{-- </body>
    </html> --}}
    </x-app-layout>


