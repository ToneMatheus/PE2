    <!DOCTYPE html>
    <html>
    <head>
        <title>Meter History</title>
        <style>
            body {
                color: white;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            .pagination {
                margin-top: 20px;
            }
            .pagination button {
                padding: 5px 10px;
                margin-right: 5px;
                cursor: pointer;
            }
            .error {
                color: red;
                font-weight: bold;
            }
        </style>
        <title>Meter History</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>

        <h1>Meter History</h1>
       @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1>Hello, {{$details[0]->first_name}}</h1>
        <div class="modal fade" id="indexModal" tabindex="-1" aria-labelledby="indexModalLabel" aria-hidden="true">
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
        </div>
        <div>
            <h1>Your meters</h1>
            @foreach ($details as $detail)
                <div class="meter">
                    <div class="meterLeft">
                        <p>EAN code: <span style="color:red">{{$detail->EAN}}</span></p>
                        <p>Type: <span style="color:red">{{$detail->type}}</span></p>
                        <p>Address: {{$detail->street}} {{$detail->number}}, {{$detail->city}}</span></p>
                        <p>Meter ID: {{$detail->meter_id}}</p>
                    </div>
                    <div class="meterRight">
                        <button type="button" class="modalOpener" value={{$detail->meter_id}}>Add index value</button>
                    </div>
                </div>
            @endforeach
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).on('click', '.modalOpener', function (e) {
                $('#indexValue').val('');
                $('#indexModal').modal('show');
                $meterID = $(this).val()

                $.ajax({
                    url: "/fetchIndex/" + $meterID,
                    method:'GET',
                    success:function(response)
                    {
                        if (response.status == 404) {
                            $('#message').addClass('alert alert-success');
                            $('#message').text(response.message);
                            $('#indexModal').modal('hide');
                        }
                        else {
                            $('#meter_id').val($meterID);
                            $('#prev').html(response.prev_index.reading_value);
                            $('#EAN').val(response.meter.EAN);
                            $('#modalEAN').html(response.meter.EAN);
                        }
                    }
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
    </body>
    </html>



