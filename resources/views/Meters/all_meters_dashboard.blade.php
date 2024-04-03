<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/all_meters_dashboard.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <h1>All meters</h1>
    <form>
        <input class="search" id="search">
    </form>
    <table class="scheduleTable">
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Address</th>
            <th>Assigned to</th>
            <th>Change assignment to</th>
            <th>Save changes</th>
        </tr>

        @foreach($results as $result)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $result->first_name.' '.$result->last_name }}</td>
                <td>{{ $result->street.' '.$result->number.', '.$result->city  }}</td>
                <td>{{ $result->assigned_to }}</td>
                <form method="POST" action="{{ route('assignment_change') }}">
                    @csrf
                    @method('PUT')
                <td>
                    <input type='hidden' name='meter_id' class="meter_id" value={{$result->meter_id}}>
                    <select name='assignment' class='assignment'>
                        @foreach($employees as $employee)
                            <option value={{$employee->employee_id}} {{$result->assigned_to == $employee->first_name ? 'selected' : ''}}>{{ $employee->first_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><button type="submit">Apply changes</button></td>
                </form>
            </tr>
        @endforeach
    </table>
    <script>
        $(document).ready(function(){
            fetch_customer_data();
 
            function fetch_customer_data(query = '')
            {
                $.ajax({
                    url:"{{ route('action') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                        $('tbody').html(data.table_data);
                        $('#total_records').text(data.total_data);
                    }

                    $(document).on('keyup', '#search', function(){
                        var query = $(this).val();
                        fetch_customer_data(query);
                    });
                })
            }
        });
    </script>
</body>
</html>