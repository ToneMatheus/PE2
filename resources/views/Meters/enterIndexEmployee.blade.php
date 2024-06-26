<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Entering index values for employees
        </h2>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto dark:text-white">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-2/5">
                <div class="border-b p-4 flex justify-between items-center">
                    <h3 class="text-lg text-black">Enter index value</h3>
                    <button id="closeModalBtn" class="text-gray-500">&times;</button>
                </div>
                <div class="p-4">
                    <form id="modalForm" method="POST" action="{{ route('submitIndex') }}">
                        <div class="form-group mb-3 text-black">
                            @csrf
                            @method('POST')
                            <input id="meter_id" name="meter_id" type="hidden">
                            <label for="index_value">Enter index value for meter <span id="modalEAN" class="modalEAN"></span>
                            <br>Previous index value: <b><span id="prev"></span></b></label>
                            <x-text-input class="block mt-1 w-full name form-control text-black" type="text" name="index_value" id="index_value" required placeholder="Enter index value" autocomplete="off"/>
            
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="cancelBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="pb-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <form>
                <div class="grid grid-cols-4 items-center">
                    <div>
                    <x-input-label for="searchBarName" :value="__('First or last name:')" />
                    <x-text-input class="searchBarName" id="searchBarName"/>
                    </div>
    
                    <div>
                    <x-input-label for="searchBarCity" :value="__('City:')" />
                    <x-text-input class="searchBarCity" id="searchBarCity"/>
                    </div>
    
                    <div>
                    <x-input-label for="searchBarStreet" :value="__('Street:')" />
                    <x-text-input class="searchBarStreet" id="searchBarStreet"/>
                    </div>
    
                    <div>
                    <x-input-label for="searchBarNumber" :value="__('Number:')" />
                    <x-text-input class="searchBarNumber" id="searchBarNumber"/>
                    </div>
                </div>
            </form>
            </div>

        <div class="searchResults" id="searchResults">
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            fetch_customer_data();
 
            function fetch_customer_data(queryName = '', queryEAN = '', queryCity = '', queryStreet = '', queryNumber = '')
            {
                $.ajax({
                    url:"{{ route('searchIndex') }}",
                    method:'GET',
                    data:{queryName:queryName, queryEAN:queryEAN, queryCity:queryCity, queryStreet:queryStreet, queryNumber:queryNumber},
                    dataType:'json',
                    success:function(data)
                    {
                        $('#searchResults').html(data.table_data);
                    }
                })
            }

            $(document).on('keyup change', '#searchBarName, #searchBarEAN, #searchBarCity, #searchBarStreet, #searchBarNumber', function(){
                $queryName = $("#searchBarName").val();
                $queryEAN = $("#searchBarEAN").val();
                $queryCity = $("#searchBarCity").val();
                $queryStreet = $("#searchBarStreet").val();
                $queryNumber = $("#searchBarNumber").val();

                fetch_customer_data($queryName, $queryEAN, $queryCity, $queryStreet, $queryNumber);
            });

            $(document).on('click', '.modalOpener', function (e) {
                $('#indexValue').val('');
                $('#modal').removeClass('hidden');
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

            const closeModal = () => {
                $('#modal').addClass('hidden');
            };

            $('#closeModalBtn').on('click', closeModal);
            $('#cancelBtn').on('click', closeModal);

            $(window).on('click', function(event) {
                if (event.target === $('#modal')[0]) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>
</html>