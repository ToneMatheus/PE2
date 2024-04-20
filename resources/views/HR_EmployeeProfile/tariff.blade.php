<!DOCTYPE html>
<html>
    <head>
        <title>Tariffs</title>
        <meta charset="utf-8"/>
        <link href="/css/tariff.css" rel="stylesheet"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
           $(document).ready(function() {
                function fetchProductByType(type){
                    $.ajax({
                        url: '/tariff/products/' + type,
                        type: 'GET',
                        success: function(product){
                        var lastTier = product.product_name;
                        var numb = parseInt(lastTier.match(/\d+/));
                        numb++;

                        var newTier = 'Tier ' + numb;

                        $('#nameSelect').empty();
                        $('#nameSelect').append($('<option>', {
                            value: newTier,
                            text: newTier
                        }));}
                    });
                }

                $('#typeSelect').change(function() {
                    var selectedType = $(this).val();
                    fetchProductByType(selectedType);
                });

                fetchProductByType($('#typeSelect').val());
            });

            function showForm() {
                document.getElementById('addTariff').style.display = 'block';
                document.getElementById('addBttn').style.display = 'none';
            }

            function confirmCancel() {
                document.getElementById('confirmCancel').style.display = 'block';
            }

            function confirmYes(){
                location.href = "{{route('tariff')}}";
            }

            function confirmNo(){
                document.getElementById('confirmCancel').style.display = 'none';
            }

            function sortTariffTable(n) {
                var table, rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
                table = document.getElementById("tariffTable");
                switching = true;
                dir = "asc";
                
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i + 1].getElementsByTagName("TD")[n];
                        
                        var xValue = isNaN(x.innerHTML) ? x.innerHTML.toLowerCase() : x.innerHTML;
                        var yValue = isNaN(y.innerHTML) ? y.innerHTML.toLowerCase() : y.innerHTML;
                        
                        if (dir == "asc") {
                            if (xValue > yValue) {
                                shouldSwitch= true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (xValue < yValue) {
                                shouldSwitch= true;
                                break;
                            }
                        }
                    }
                    
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        switchCount++;
                    } else {
                        if (switchCount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                        }
                    }
                }
            }

            function swapRows(x, y){            
                var tempRow = x.innerHTML;

                x.innerHTML = y.innerHTML;
                y.innerHTML = tempRow;
            }
        </script>
    </head>
    <body>
        <h1>Products</h1>

        <h2>Electrical</h2>

        @php
            $types = [];
        @endphp

        @foreach ($productTariffs as $productTariff)

            @if (!in_array($productTariff->type, $types))
                @php
                    $types[] = $productTariff->type;
                @endphp
            @endif
        @endforeach

        @foreach ($types as $type)
            <h3>{{ $type }}</h3>
            
            <table id="tariffTable">
                <tr>
                    <th onclick="sortTariffTable(0)">Name</th>
                    <th onclick="sortTariffTable(1)">Range Min</th>
                    <th onclick="sortTariffTable(2)">Range Max</th>
                    <th onclick="sortTariffTable(3)">Rate</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                @foreach ($productTariffs as $productTariff)
                    @if ($productTariff->type === $type)
                        @if (request()->get('action') === 'edit')
                            @if($productTariff->product_id == request()->get('pID'))
                            <form id="changeTariff" method="post" action="{{ route('tariff.edit', ['pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}">
                                @csrf
                                <tr>
                                    <td>{{$productTariff->product_name}}</td>
                                    <td>
                                        <input type="number" name="rangeMin" id="rangeMin" min="0" value="{{$productTariff->range_min}}" required/> 
                                    </td>
                                    <td>
                                        <input type="number" name="rangeMax" id="rangeMax" min="0" value="{{$productTariff->range_max}}"/> 
                                    </td>
                                    <td>
                                        <input type="number" name="rate" id="rate" min="0.01" max="1" step="0.01" value="{{$productTariff->rate}}" required/> 
                                    </td>
                                    <td>
                                        <input type="submit" name="submitChangeTariff" value="Save"/>
                                        <button type="button" onclick="confirmCancel()">Cancel</button>
                                    </td>
                                </tr>
                            </form>
                            @else
                                <tr>
                                    <td>{{$productTariff->product_name}}</td>
                                    <td>{{$productTariff->range_min}}</td>
                                    <td>{{$productTariff->range_max}}</td>
                                    <td>{{$productTariff->rate}}</td>
                                    <td>
                                        <a href="{{ route('tariff', ['action' => 'edit', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}'">
                                            <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon"/>
                                        </a>
                                    </td>
                                    <td>
                                    <a href="{{ route('tariff.delete', ['action' => 'delete', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}">
                                            <img src="{{asset('./images/trashIcon.png')}}" alt="trash Icon" id="trashIcon"/>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>{{$productTariff->product_name}}</td>
                                <td>{{$productTariff->range_min}}</td>
                                <td>{{$productTariff->range_max}}</td>
                                <td>{{$productTariff->rate}}</td>
                                <td>
                                    <a href="{{ route('tariff', ['action' => 'edit', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}'">
                                        <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon"/>
                                    </a>
                                </td>
                                <td>
                                <a href="{{ route('tariff.delete', ['action' => 'delete', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}">
                                        <img src="{{asset('./images/trashIcon.png')}}" alt="trash Icon" id="trashIcon"/>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            </table>
        @endforeach

        <button class="addBttn" id="addBttn" onclick="showForm()">+</button>

        <form id="addTariff" method="post" action="{{ route('tariff.add') }}">
            @csrf

            <label for="name">Name:</label>
            <select name="name" id="nameSelect">
            
            </select>

            <label for="type">Type:</label>
            <select name="type" id="typeSelect">
                @foreach ($types as $type)
                    <option value="{{$type}}">{{$type}}</option>
                @endforeach
            </select>

            <label for="rangeMin">Range Minimum:</label>
            <input type="number" name="rangeMin" id="rangeMin" min="0" required/>

            <label for="rangeMax">Range Maximum:</label>
            <input type="number" name="rangeMax" id="rangeMax" min="0" placeholder="/"/>

            <label for="rate">Rate:</label>
            <input type="number" name="rate" id="rate" min="0.1" max="1" step="0.01" required/>

            <div id="addBttns">
                <input type="submit" name="submitTariff"/>
                <button type="button" id="cancel" onclick="confirmCancel()">Cancel</button>
            </div>
        </form>

        <div id="confirmCancel">
            <p>Anything unsaved will be lost. Are you sure you want to quit?</p>
            <button id="confirmYes" onclick="confirmYes()">Yes</button>
            <button id="confirmNo" onclick="confirmNo()">No</button>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif
    </body>
</html>