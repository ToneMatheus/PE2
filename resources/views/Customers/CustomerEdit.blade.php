<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <style>
        .edit-form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.1);
        }

        .edit-form label {
            display: block;
            margin-bottom: 5px;
        }

        .edit-form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .edit-form button[type="submit"], .edit-form button[type="button"]{
            text-decoration: none;
            padding: 10px 20px;
            border: none;
            background-color: #2b3db6;
            color: white;
            cursor: pointer;
        }

        .edit-form button[type="submit"]:hover .edit-form button[type="button"]:hover{
            background-color: #0f1b68;
        }

        #addDiscount {
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
                function fetchProductsByType(type){
                    $.ajax({
                        url: '/products/' + type,
                        type: 'GET',
                        success: function(data){
                            $('#productSelect').empty();
                            $.each(data, function(index, product){
                                var option = $('<option>', {
                                    value: product.id,
                                    text: product.product_name
                                });
                                if (product.product_name == '{{ $productTariff->product_name }}' && product.type == '{{ $productTariff->type }}') {
                                    option.prop('selected', true);
                                }
                                $('#productSelect').append(option);
                            });
                        }
                    });
                }

                $('#typeSelect').change(function() {
                    var selectedType = $(this).val();
                    fetchProductsByType(selectedType);
                });

                fetchProductsByType($('#typeSelect').val());
            });

            function addDiscount(toggle){
                switch(toggle){
                    case 0:
                        document.getElementById('addDiscount').style.display = 'none';
                        document.getElementById('bttns').style.display = 'block';
                        break;
                    case 1:
                        document.getElementById('addDiscount').style.display = 'block';
                        document.getElementById('bttns').style.display = 'none';
                        break;
                }
            }

            function calculateDiscount(currentRate){
                var val;
                var input = document.getElementById('percentage').value;
                var calculatedRate = document.getElementById('calculatedRate');
                var output = document.getElementById('newRate');

                input /= 100;
                val = currentRate * input;
                val = currentRate - val;

                calculatedRate.innerHTML = '€' + val.toFixed(2) + ' Kw/h';
                output.value = val;
                
            }
    </script>
</head>
<body>
    <h1>Edit Customer</h1>

    <form class="edit-form" action="{{ route('customer.update', ['id' => $customer->id, 'cpID' => $contractProduct->cpID]) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this customer?');">
        @csrf
        @method('PUT')

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="{{ $customer->last_name }}" required>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="{{ $customer->first_name }}" required>

        <label for="phone_nbr">Phone Number:</label>
        <input type="text" id="phone_nbr" name="phone_nbr" value="{{ $customer->phone_nbr }}" required>

        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="{{ $customer->company_name }}">

        <label for="product">Product:</label>
        <select name="product" id="productSelect">

        </select>

        <select name="type" id="typeSelect">
            @foreach ($types as $type)
                @if($productTariff->type == $type->type)
                    <option value="{{$type->type}}" selected>{{$type->type}}</option>
                @else
                    <option value="{{$type->type}}">{{$type->type}}</option>
                @endif
            @endforeach
        </select>
        
        @if(isset($discount))

            @php
                $oldRate = $productTariff->rate;
                $newRate = $discount->rate;

                $percentage = (($oldRate - $newRate) / $oldRate) * 100;
                $roundedPercentage = round($percentage, 2);
            @endphp

            <div>
                <h2>Discount</h2>
                <p>New rate: {{$discount->rate}}</p>
                <p>Percentage: {{$roundedPercentage}}%</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Update</button>
        <button type="button" onclick="window.location='{{ url('/customerGridView') }}'">Back</button>
    </form>

    <p>Tariff: </p>

    <ul>
        <li>RangeMin: {{$productTariff->range_min}} Kw/h</li>
        <li>RangeMax: {{$productTariff->range_max}} Kw/h</li>
        <li>Rate: €{{$productTariff->rate}} Kw/h</li>
    </ul>

    @if(!isset($discount))
        <button onclick="addDiscount(1)">Add Discount</button>
    @endif

    <form id="addDiscount" method="post" action="{{ route('customer.discount', ['cpID' => $contractProduct->cpID, 'id' => $customer->id]) }}">
            @csrf
            <label for="percentage">Percentage: </label>
            <input id="percentage" name="percentage" type="number"  onkeyup="calculateDiscount(<?php echo $productTariff->rate; ?>)" min='2' max='98' required/>

            @php
                $currentDateTime = new DateTime('now');
                $currentDate = $currentDateTime->format('Y-m-d');

                $maxDateTime = $currentDateTime->modify('+3 month');
                $maxDate = $maxDateTime->format('Y-m-d');
            @endphp

            <label for="startDate">Start Date: </label>
            <input id="startDate" name="startDate" type="date" min="{{$currentDate}}" max="{{$maxDate}}" data-date-format="YYYY MM DD" value="{{$currentDate}}" required/>

            <label for="endDate">End Date: </label>
            <input id="endDate" name="endDate" type="date" min="{{$currentDate}}" max="{{$maxDate}}" data-date-format="YYYY MM DD" required/>
                
            <p id="calculatedRate"></p>
            <input type="hidden" id="newRate" name="newRate"/>

            <input type="submit" name="submitDiscount"/>
            <button type="button" onclick="addDiscount(0)">Cancel</button>
        </form>
    </body>
</body>
</html>