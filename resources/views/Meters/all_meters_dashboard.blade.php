{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="/css/all_meters_dashboard.css" rel="stylesheet" type="text/css"/>
    <title>All meters dashboard</title>
</head>
<body> --}}
<x-app-layout title="All meters dashboard">
    <h1>All meters</h1>
    {{-- <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form method="POST" action="/bulk_assignment_change">
                <div class="modal-body">
                    <div class="form-group mb-3">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="enter">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div> --}}
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
    <form>
        <p>Search by:</p>
        <label for="searchBarName">First or last name:</label>
        <input class="searchBarName" id="searchBarName">
        <label for="searchBarCity">City:</label>
        <input class="searchBarCity" id="searchBarCity">
        <label for="searchBarStreet">Street:</label>
        <input class="searchBarStreet" id="searchBarStreet">
        <label for="searchBarNumber">Number:</label>
        <input class="searchBarNumber" id="searchBarNumber">
        <label for="searchAssigned">Assigned to:</label>
        <select class="searchAssigned" id="searchAssigned">
            <option selected value="">Select</option>
            @foreach($employees as $employee)
                <option value={{$employee->first_name}}>{{ $employee->first_name }}</option>
            @endforeach
        </select>
        <button type="button" id="modalOpener">Bulk assignment</button>
        {{-- <x-input-label for="searchBar" :value="__('Username')" />
        <x-text-input id="searchBar" class="block mt-1 w-full" type="text" name="searchBar" /> --}}
    </form>
    
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">SN</th>
            <th scope="col" class="px-6 py-3">Name</th>
            <th scope="col" class="px-6 py-3">Address</th>
            <th scope="col" class="px-6 py-3">Assigned to</th>
            <th scope="col" class="px-6 py-3">Change assignment to</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
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
