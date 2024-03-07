<!DOCTYPE html>
<html>
    <head>
        <title>Customer Contract</title>
        <meta charset="utf-8"/>
        <link href="/css/contractProduct.css" rel="stylesheet"/>
        <script>
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
                        break;
                    case 1:
                        document.getElementById('product').style.display = 'none';
                        break;
                }
            }
        </script>
    </head>
    <body>
        <h1>Contract Details</h1>
        <div>
            <h2>{{ $contractProduct->firstName }} {{ $contractProduct->lastName }}</h2>
            <p>Customer ID: {{$contractProduct->customerID}}</p>
            <p>Phone number: {{$contractProduct->phoneNumber}}</p>

            @if (isset($contractProduct->companyName))
                <p>Company: {{$contractProduct->companyName}}</p>
            @endif
        </div>

        <div>
            <h2>Contract:</h2>
            <p>Start Date: {{$contractProduct->startDate}}</p>

            <p id="product">Product: {{$contractProduct->productName}} {{$productTariff->type}}
                <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon" onclick="showEdit(1)"/>
            </p>


            <p>Tariff: </p>

            <ul>
                <li>RangeMin: {{$productTariff->rangeMin}} Kw/h</li>
                <li>RangeMax: {{$productTariff->rangeMax}} Kw/h</li>
                <li>Rate: €{{$productTariff->rate}} Kw/h</li>
            </ul>
        </div>

        @if(isset($discount))

            @php
                $oldRate = $productTariff->rate;
                $newRate = $discount->rate;

                $percentage = (($oldRate - $newRate) / $oldRate) * 100;
            @endphp

            <div>
                <h2>Discount</h2>
                <p>New rate: {{$discount->rate}}</p>
                <p>Percentage: {{$percentage}}%</p>
            </div>
        @endif

        <div id="bttns">
            @if(!isset($discount))
                <button onclick="addDiscount(1)">Add Discount</button>
            @endif
        </div>

        <form id="addDiscount" method="post" action="{{ route('cp.discount', ['cpID' => $contractProduct->ID, 'ccID' => $contractProduct->customerContractID, 'pID' => $contractProduct->productID]) }}">
            @csrf
            <label for="percentage">Percentage: </label>
            <input id="percentage" name="percentage" type="number"  onkeyup="calculateDiscount(<?php echo $productTariff->rate; ?>)" required/>

            <p id="calculatedRate"></p>
            <input type="hidden" id="newRate" name="newRate"/>

            <input type="submit" name="submitDiscount"/>
            <button type="button" onclick="addDiscount(0)">Cancel</button>
        </form>

    </body>
</html>