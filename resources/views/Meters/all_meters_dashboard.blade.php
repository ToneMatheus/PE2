<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/all_meters_dashboard.css" rel="stylesheet" type="text/css"/>
    <title>Document</title>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <h1>All meters</h1>
    <form>
        <p>Search by:</p>
        <label for="searchBarName">First or last name:</label>
        <input class="searchBarName" id="searchBarName">
        <label for="searchBarAddress">Address:</label>
        <input class="searchBarAddress" id="searchBarAddress">
        <label for="searchAssigned">Assigned to:</label>
        <select class="searchAssigned" id="searchAssigned">
            @foreach($employees as $employee)
                <option value={{$employee->first_name}}>{{ $employee->first_name }}</option>
            @endforeach
        </select>
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
 
            function fetch_customer_data(queryName = '', queryAddress = '', queryAssigned = '')
            {
                $.ajax({
                    url:"{{ route('search') }}",
                    method:'GET',
                    data:{queryName:queryName, queryAddress:queryAddress, queryAssigned:queryAssigned},
                    dataType:'json',
                    success:function(data)
                    {
                        $('tbody').html(data.table_data);
                    }
                })
            }

            $(document).on('keyup change', '#searchBarName, #searchBarAddress, #searchAssigned', function(){
                $queryName = $("#searchBarName").val();
                $queryAddress = $("#searchBarAddress").val();
                $queryAssigned = $("#searchAssigned").val();

                console.log($queryAssigned);
                fetch_customer_data($queryName, $queryAddress, $queryAssigned);
            });
        });
    </script>
</body>
</html>