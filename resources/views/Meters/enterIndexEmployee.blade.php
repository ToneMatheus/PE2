<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="/css/enterIndexEmployee.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <h1>Enter index values</h1>
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
    </form>

    <div class="searchResults" id="searchResults">
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            fetch_customer_data();
 
            function fetch_customer_data(queryName = '', queryCity = '', queryStreet = '', queryNumber = '')
            {
                $.ajax({
                    url:"{{ route('searchIndex') }}",
                    method:'GET',
                    data:{queryName:queryName, queryCity:queryCity, queryStreet:queryStreet, queryNumber:queryNumber},
                    dataType:'json',
                    success:function(data)
                    {
                        $('#searchResults').html(data.table_data);
                    }
                })
            }

            $(document).on('keyup change', '#searchBarName, #searchBarCity, #searchBarStreet, #searchBarNumber', function(){
                $queryName = $("#searchBarName").val();
                $queryCity = $("#searchBarCity").val();
                $queryStreet = $("#searchBarStreet").val();
                $queryNumber = $("#searchBarNumber").val();

                fetch_customer_data($queryName, $queryCity, $queryStreet, $queryNumber);
            });
        });
    </script>
</body>
</html>