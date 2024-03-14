<!DOCTYPE html>
<html>
    <head>
        <title>Customer Contract</title>
        <meta charset="utf-8"/>
        <link href="/css/contractProduct.css" rel="stylesheet"/>
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
                                $('#productSelect').append($('<option>', {
                                    value: product.id,
                                    text: product.product_name
                                }));
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

                function showEdit(toggle){
                    switch(toggle){
                        case 0:
                            document.getElementById('product').style.display = 'block';
                            document.getElementById('editProduct').style.display = 'none';
                            break;
                        case 1:
                            document.getElementById('product').style.display = 'none';
                            document.getElementById('editProduct').style.display = 'block';
                            break;
                    }
                }
        </script>
    </head>
    <body>
        <h1>Contract Details</h1>
        <div>
            <h2>{{ $contractProduct->first_name }} {{ $contractProduct->last_name }}</h2>
            <p>Customer ID: {{$contractProduct->uID}}</p>
            <p>Phone number: {{$contractProduct->phone_nbr}}</p>

            @if (isset($contractProduct->company_name))
                <p>Company: {{$contractProduct->company_name}}</p>
            @endif
        </div>

        <div>
            <h2>Contract:</h2>
            <p>Start Date: {{$contractProduct->cpStartDate}}</p>

            <p id="product">Product: {{$contractProduct->productName}} {{$productTariff->type}}
                <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon" onclick="showEdit(1)"/>
            </p>

            <form id="editProduct" method="post" action="{{ route('cp.edit', ['cpID' => $contractProduct->cpID]) }}">
                @csrf
                <label for="product">Product:</label>
                <select name="product" id="productSelect">

                </select>
                <select name="type" id="typeSelect">
                    @foreach ($types as $type)
                        <option value="{{$type->type}}">{{$type->type}}</option>
                    @endforeach
                </select>
                <input type="submit" name="submitProduct"/>
                <button type="button" onclick="showEdit(0)">Cancel</button>
            </form>

            <p>Tariff: </p>

            <ul>
                <li>RangeMin: {{$productTariff->range_min}} Kw/h</li>
                <li>RangeMax: {{$productTariff->range_max}} Kw/h</li>
                <li>Rate: €{{$productTariff->rate}} Kw/h</li>
            </ul>
        </div>

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

        <div id="bttns">
            @if(!isset($discount))
                <button onclick="addDiscount(1)">Add Discount</button>
            @endif
        </div>

        <form id="addDiscount" method="post" action="{{ route('cp.discount', ['cpID' => $contractProduct->cpID]) }}">
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

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif 
    </body>
</html>