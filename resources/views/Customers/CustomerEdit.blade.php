@php
    $productsByType = [];
    foreach ($products as $product) {
        $productsByType[$product->type][] = $product;
    }

    $i = 1;
@endphp

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

        .discount {
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var productsByType = <?php echo json_encode($productsByType); ?>;

            // Update product options on type change
            $('.edit-form').each(function() {
                var form = this;
                var productSelect = $(form).find('.productSelect');
                var typeSelect = $(form).find('.typeSelect');

                typeSelect.on('change', function() {
                    var selectedType = $(this).val();
                    var products = productsByType[selectedType] || [];
                    updateProductOptions(productSelect, products);
                });

                // Initialize product options based on the initial type
                var initialType = typeSelect.val();
                var initialProducts = productsByType[initialType] || [];
                updateProductOptions(productSelect, initialProducts);
            });

            function updateProductOptions(productSelect, products) {
                productSelect.empty();
                products.forEach(function(product) {
                    productSelect.append($('<option>', {
                        value: product.id,
                        text: product.product_name
                    }));
                });
            }
        });

        function showDiscount(toggle, counter) {
            var percentageInput = document.getElementById('percentage' + counter);
            var startDateInput = document.getElementById('startDate' + counter);
            var endDateInput = document.getElementById('endDate' + counter);
            var product = document.getElementById('product' + counter);
            var submit = document.getElementById('submit' + counter);

            if (toggle) {
                document.getElementById('discount' + counter).style.display = 'block';
                document.getElementById('showBttn' + counter).style.display = 'none';
                percentageInput.required = true;
                startDateInput.required = true;
                endDateInput.required = true;

                product.style.display = 'none';
                submit.innerHTML = 'Update Discount'
            } else {
                document.getElementById('discount' + counter).style.display = 'none';
                document.getElementById('showBttn' + counter).style.display = 'block';
                percentageInput.required = false;
                startDateInput.required = false;
                endDateInput.required = false;

                product.style.display = 'block';
                submit.innerHTML = 'Update Product'
            }
        }

        function calculateDiscount(currentRate, counter){
            var val;
            var input = document.getElementById('percentage' + counter).value;
            var calculatedRate = document.getElementById('calculatedRate' + counter);
            var output = document.getElementById('newRate' + counter);

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

    <form class="edit-form" action="{{ route('customer.update', ['id' => $customer->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this customer?');">
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

    <h2>Meters</h2>

    @foreach ($cps as $cp)
        @php 
            $i++;
        @endphp
        <form class="edit-form" method="post" action="{{ route('customer.contractProduct', ['id' => $customer->id, 'oldCpID' => $cp->cpID, 'cID' => $cp->cID, 'mID' => $cp->mID]) }}">
                @csrf
                <p>{{$cp->street}} {{$cp->number}} {{$cp->box}}, {{$cp->city}} {{$cp->postal_code}}</p>

                <ul>
                    <li>RangeMin: {{$cp->range_min}} Kw/h</li>
                    <li>RangeMax: {{$cp->range_max}} Kw/h</li>
                    <li>Rate: €{{$cp->rate}} Kw/h</li>
                </ul>

                <div id="product{{$i}}">
                    <label for="product">Product:</label>
                    <select name="product" class="productSelect">
                        @foreach ($products as $product)
                            @if($cp->product_name == $product->product_name)
                                <option value="{{ $product->id }}" selected>{{ $product->product_name }}</option>
                            @else
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endif
                        @endforeach
                    </select>

                    <select name="type" class="typeSelect">
                        @foreach ($types as $type)
                            @if($cp->type == $type->type)
                                <option value="{{$type->type}}" selected>{{$type->type}}</option>
                            @else
                                <option value="{{$type->type}}">{{$type->type}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                @if(sizeof($discounts) > 0)
                    @foreach($discounts as $discount)
                        @if($discount->contract_product_id == $cp->cpID)
                            @php
                                $oldRate = $cp->rate;
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
                    @endforeach
                @else
                    <button type="button" id="showBttn{{$i}}" onclick="showDiscount(1, '{{$i}}')">Add Discount</button>

                    <div id="discount{{$i}}" class="discount" style="display: none;">
                        <label for="percentage{{$i}}">Percentage: </label>
                        <input id="percentage{{$i}}" name="percentage" type="number"  onkeyup="calculateDiscount(<?php echo $cp->rate; ?>, '{{$i}}')" min='2' max='98'/>

                        @php
                            $currentDateTime = new DateTime('now');
                            $currentDate = $currentDateTime->format('Y-m-d');

                            $maxDateTime = $currentDateTime->modify('+3 month');
                            $maxDate = $maxDateTime->format('Y-m-d');
                        @endphp

                        <label for="startDate{{$i}}">Start Date: </label>
                        <input id="startDate{{$i}}" name="startDate" type="date" min="{{$currentDate}}" max="{{$maxDate}}" data-date-format="YYYY MM DD" value="{{$currentDate}}"/>

                        <label for="endDate{{$i}}">End Date: </label>
                        <input id="endDate{{$i}}" name="endDate" type="date" min="{{$currentDate}}" max="{{$maxDate}}" data-date-format="YYYY MM DD"/>
                            
                        <p id="calculatedRate{{$i}}"></p>
                        <input type="hidden" id="newRate{{$i}}" name="newRate"/>

                        <button type="button" onclick="showDiscount(0, '{{$i}}')">Cancel Discount</button>
                    </div>
                @endif

                <p></p>

                <button type="submit" id="submit{{$i}}">Update Product</button>
                <button type="button" onclick="window.location='{{ url('/customerGridView') }}'">Back</button>
        </form>
        @endforeach
    </body>
</body>
</html>