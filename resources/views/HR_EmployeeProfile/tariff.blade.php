<!DOCTYPE html>
<html>
    <head>
        <title>Tariffs</title>
        <meta charset="utf-8"/>
        <link href="/css/tariff.css" rel="stylesheet"/>
        <script>    
            function showForm() {
                document.getElementById('addTariff').style.display = 'block';
                document.getElementById('addBttn1').style.display = 'none';
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

            function checkAddTariff(){
                var name = document.getElementById('name').value;
                var type = document.getElementById('type').value;
                var rangeMin = document.getElementById('rangeMin').value;
                var rangeMax = document.getElementById('rangeMax').value;
                var rate = document.getElementById('rate').value;

                var error = document.getElementById('error1');

                if(!name || !type || !rangeMin || !rate){
                    error.innerHTML = 'Fill in all fields';
                    return false;
                }

                /*if(rangeMax){
                    if (rangeMin > rangeMax){
                        error.innerHTML = 'Range Minimum should be smaller than Range Maximmum';
                        return false;
                    }
                }*/

                return true;
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
                                        <input type="number" name="rangeMin" id="rangeMin" min="0" value="{{$productTariff->rangeMin}}"/> 
                                    </td>
                                    <td>
                                        <input type="number" name="rangeMax" id="rangeMax" min="0" value="{{$productTariff->rangeMax}}"/> 
                                    </td>
                                    <td>
                                        <input type="number" name="rate" id="rate" min="0" max="1" step="0.01" value="{{$productTariff->rate}}"/> 
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
                                        <a href="{{ URL::route('tariff', ['action' => 'edit', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}'">
                                            <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon"/>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('tariff', ['action' => 'delete', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}">
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
                                    <a href="{{ URL::route('tariff', ['action' => 'edit', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}'">
                                        <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon"/>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tariff', ['action' => 'delete', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}">
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

        <form id="addTariff" method="post" action="<?php echo($_SERVER['PHP_SELF']) ?>" onsubmit="return checkAddTariff()">
            @csrf

            <label for="name">Name:</label>
            <select name="name" id="name">
                <option value="Tier 1">Tier 1</option>
                <option value="Tier 2">Tier 2</option>
                <option value="Tier 3">Tier 3</option>
            </select>

            <label for="type">Type:</label>
            <select name="type" id="type">
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
            </select>

            <label for="rangeMin">Range Minimum:</label>
            <input type="number" name="rangeMin" id="rangeMin" min="0"/>            <!--check of rangeMin < rangeMax-->

            <label for="rangeMax">Range Maximum:</label>
            <input type="number" name="rangeMax" id="rangeMax" min="0" placeholder="/"/>

            <label for="rate">Rate:</label>
            <input type="number" name="rate" id="rate" min="0" max="1" step="0.01"/>

            <p class="error" id="error1"></p>

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

        <h2>Customer</h2>
        
        <form id="searchBarForm">
            <input type="text" name="searchBar"/>
            <input type="submit" name="submitSearch"/>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Product Name</th>
                <th>Type</th>
                <th>Rate</th>
                <th>Edit</th>
            </tr>

            @foreach ($contractProducts as $contractProduct)
                <tr>
                    <td>{{$contractProduct->id}}</td>
                    <td>{{$contractProduct->name}}</td>
                    <td>{{$contractProduct->product_name}}</td>
                    <td>{{$contractProduct->type}}</td>
                    <td>{{$contractProduct->rate}}</td>
                    <td>
                        <a href="{{ route('contractProduct', ['cpID' => $contractProduct->id]) }}"> 
                            <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon"/>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </body>
</html>