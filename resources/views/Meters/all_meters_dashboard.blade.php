<!DOCTYPE html>
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
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <h1>All meters</h1>
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
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
        <button type="button" class="modalOpener">Bulk assignment</button>
        {{-- <x-input-label for="searchBar" :value="__('Username')" />
        <x-text-input id="searchBar" class="block mt-1 w-full" type="text" name="searchBar" /> --}}
    </form>
    
    <table class="scheduleTable">
        <thead>
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Address</th>
            <th>Assigned to</th>
            <th>Change assignment to</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
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
</body>
</html>
