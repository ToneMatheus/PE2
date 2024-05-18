<x-app-layout title="Tariff">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tariffs') }}
        </h2>

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
                                }));
                            }
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
    </x-slot>

    <body class="bg-white dark:bg-gray-800">
        <h1 class="text-2xl font-bold">Products</h1>

        <h2 class="text-xl font-bold">Electrical</h2>

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
            <h3 class="text-lg font-semibold mt-4">{{ $type }}</h3>
            
            <div class="overflow-x-auto">
                <table id="tariffTable" class="table-auto border-collapse border border-gray-200 mt-2">
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4"  onclick="sortTariffTable(0)">Name</th>
                        <th class="py-2 px-4" onclick="sortTariffTable(1)">Range Min</th>
                        <th class="py-2 px-4" onclick="sortTariffTable(2)">Range Max</th>
                        <th class="py-2 px-4" onclick="sortTariffTable(3)">Rate</th>
                        <th class="py-2 px-4">Edit</th>
                        <th class="py-2 px-4">Delete</th>
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
                                            <input class="bg-blue-500 text-white font-bold py-2 px-4 rounded" type="submit" name="submitChangeTariff" value="Save"/>
                                            <button class="bg-red-500 text-white font-bold py-2 px-4 rounded" type="button" onclick="confirmCancel()">Cancel</button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </form>
                                @else
                                    <tr>
                                        <td>{{$productTariff->product_name}}</td>
                                        <td>{{$productTariff->range_min}}</td>
                                        <td>{{$productTariff->range_max}}</td>
                                        <td>{{$productTariff->rate}}</td>
                                        <td class="py-2 px-3 text-center">
                                            <a href="{{ route('tariff', ['action' => 'edit', 'pID' => $productTariff->product_id, 'tID' => $productTariff->tariff_id]) }}'">
                                                <img src="{{asset('./images/editIcon.png')}}" alt="edit Icon" id="editIcon"/>
                                            </a>
                                        </td>
                                        <td class="py-2 px-3 text-center">
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
            </div>
        @endforeach

        <button id="addBttn" class="bg-blue-500 text-white font-bold py-2 px-4 mt-4" onclick="showForm()">+</button>

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
                <input type="submit" name="submitTariff" class="bg-blue-500 text-white font-bold py-2 px-4 mr-2"/>
                <button type="button" id="cancel" onclick="confirmCancel()" class="bg-red-500 text-white font-bold py-2 px-4 mr-2">Cancel</button>
            </div>
        </form>

        <div id="confirmCancel" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.385-1.54 1.414-2.586L13.415 4.415a1.5 1.5 0 00-2.83 0L3.122 15.414C2.152 16.46 2.997 18 4.536 18z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Cancel</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Anything unsaved will be lost. Are you sure you want to quit?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="confirmNo()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">No</button>
                        <button type="button" onclick="confirmYes()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Yes</button>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="text-red-500">{{ $error }}</p>
            @endforeach
        @endif
    </body>
</x-app-layout>