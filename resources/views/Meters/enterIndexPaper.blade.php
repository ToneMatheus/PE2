<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paper Index Dashboard</title>
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
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
    <div class="modal fade" id="indexModal" tabindex="-1" aria-labelledby="indexModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indexModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form method="POST" action="{{ route('submitIndex') }}">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        @csrf
                        @method('POST')
                        <input id="meter_id" name="meter_id" type="hidden">
                        <label for="index_value">Enter index value for meter <span id="modalEAN" class="modalEAN"></span></label>
                        <input id="index_value" name="index_value" type="text" required class="name form-control">
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
                    url:"{{ route('searchIndexPaper') }}",
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
                $('#indexModal').modal('show');
                $meterID = $(this).val()

                $.ajax({
                    url: "/fetchEAN/" + $meterID,
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
                            $('#modalEAN').html(response.result.EAN);
                        }
                    }
                })
            })
        });
    </script>
</body>
</html>