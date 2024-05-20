<x-app-layout title="All meters dashboard">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            All meters
        </h2>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto dark:text-white">
        <div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-2/5">
                <div class="border-b p-4 flex justify-between items-center">
                    <h3 class="text-lg">Bulk assignment</h3>
                    <button id="closeModalBtn" class="text-gray-500">&times;</button>
                </div>
                <div class="p-4">
                    <form id="modalForm" method="POST" action="/bulk_assignment_change">
                        <div class="mb-3">
                            @csrf
                            @method('POST')
                            <p>Change assignment of 
                                <select class="previous_employee" id="previous_employee" name="previous_employee">
                                    <option selected value="">Select</option>
                                    @foreach($employees as $employee)
                                        <option value={{$employee->employee_id}}>{{ $employee->first_name }}</option>
                                    @endforeach
                                </select>
                                to 
                                <select class="next_employee" id="next_employee" name="next_employee">
                                    <option selected value="">Select</option>
                                    @foreach($employees as $employee)
                                        <option value={{$employee->employee_id}}>{{ $employee->first_name }}</option>
                                    @endforeach
                                </select>
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
            <div class="grid grid-cols-6 items-center">
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

                <div>
                <x-input-label for="searchAssigned" :value="__('Assigned to:')" />
                <select class="searchAssigned" id="searchAssigned">
                    <option selected value="">Select</option>
                    @foreach($employees as $employee)
                        <option value={{$employee->first_name}}>{{ $employee->first_name }}</option>
                    @endforeach
                </select>
                </div>

                <div>
                <x-primary-button type="button" id="modalOpener">Bulk assignment</x-primary-button>
                </div>
            </div>
        </form>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-3 py-2 text-center border">SN</th>
                <th scope="col" class="px-3 py-2 text-center border">Name</th>
                <th scope="col" class="px-3 py-2 text-center border">Address</th>
                <th scope="col" class="px-3 py-2 text-center border">Assigned to</th>
                <th scope="col" class="px-3 py-2 text-center border">Change assignment to</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
    </div>
    <script>
        // Get elements
        const openModalBtn = document.getElementById('modalOpener');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const modal = document.getElementById('modal');
        const modalForm = document.getElementById('modalForm');

        // Open modal
        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Close modal
        const closeModal = () => {
            modal.classList.add('hidden');
        };
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside of it
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            fetch_customer_data();

            function fetch_customer_data(queryName = '', queryCity = '', queryStreet = '', queryNumber = '', queryAssigned = '')
            {
                $.ajax({
                    url:"{{ route('search') }}",
                    method:'GET',
                    data:{queryName:queryName, queryCity:queryCity, queryStreet:queryStreet, queryNumber:queryNumber, queryAssigned:queryAssigned},
                    dataType:'json',
                    success:function(data)
                    {
                        $('tbody').html(data.table_data);
                    }
                })
            }

            $(document).on('keyup change', '#searchBarName, #searchBarCity, #searchBarStreet, #searchBarNumber, #searchAssigned', function(){
                $queryName = $("#searchBarName").val();
                $queryCity = $("#searchBarCity").val();
                $queryStreet = $("#searchBarStreet").val();
                $queryNumber = $("#searchBarNumber").val();
                $queryAssigned = $("#searchAssigned").val();

                console.log($queryAssigned);
                fetch_customer_data($queryName, $queryCity, $queryStreet, $queryNumber, $queryAssigned);
            })

            $(document).on('click', '.modalOpener', function (e) {
                $('#employeeModal').modal('show');
            })
        })
    </script>
</x-app-layout>
{{-- </body>
</html> --}}
