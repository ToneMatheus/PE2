<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="/css/enterIndexEmployee.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <div class="pageContainer">
    <div class="modal fade" id="indexModal" tabindex="-1" aria-labelledby="indexModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indexModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Enter index value for <span class="modalEAN"></span></label>
                        <input type="text" required class="name form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <h1>Enter index values</h1>
    <form>
        <p>Search by:</p>
        <label for="searchBarName">First or last name:</label>
        <input class="searchBarName" id="searchBarName">
        <label for="searchBarEAN">EAN:</label>
        <input class="searchBarEAN" id="searchBarEAN">
        <label for="searchBarCity">City:</label>
        <input class="searchBarCity" id="searchBarCity">
        <label for="searchBarStreet">Street:</label>
        <input class="searchBarStreet" id="searchBarStreet">
        <label for="searchBarNumber">Number:</label>
        <input class="searchBarNumber" id="searchBarNumber">
    </form>

    <div class="searchResults" id="searchResults">
    </div>
    </div>

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
                $('#indexModal').modal('show');
                $userId = $(this).val()
            })
        });
    </script>
</body>
</html>