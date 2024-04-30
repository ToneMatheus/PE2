<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gridview</title>
    <link href="{{ asset('css/gridview.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <form id="searchForm">
                <input type="text" name="search" id="searchInput" required value="{{ request('search') }}">
                <button type="submit">Search</button>
            </form>
            <a href="{{ route('customerGridView') }}" class="reset-button">Clear</a>
        </div>
        <table id="customerTable">
            <thead>
                <th><a href="#" class="sort" data-column="id">ID</a></th>
                <th><a href="#" class="sort" data-column="last_name">Last Name</a></th>
                <th><a href="#" class="sort" data-column="first_name">First Name</a></th>
                <th><a href="#" class="sort" data-column="phone_nbr">Phone Number</a></th>
                <th><a href="#" class="sort" data-column="company_name">Company Name</a></th>
                <th><a href="#" class="sort" data-column="is_company">Is Company</a></th>
                <th><a href="#" class="sort" data-column="email">Email</a></th>
                <th><a href="#" class="sort" data-column="birth_date">Birth Date</a></th>
                <th><a href="#" class="sort" data-column="is_active">Is Active</a></th>
                <th><a href="#" class="sort" data-column="start_date">Start Date</a></th>
                <th><a href="#" class="sort" data-column="end_date">End Date</a></th>
                <th><a href="#" class="sort" data-column="type">Type</a></th>
                <th><a href="#" class="sort" data-column="price">Price</a></th>
                <th><a href="#" class="sort" data-column="status">Status</a></th>
                <th>Edit</th>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->last_name }}</td>
                        <td>{{ $customer->first_name }}</td>
                        <td>{{ $customer->phone_nbr }}</td>
                        <td>{{ $customer->company_name }}</td>
                        <td>{{ $customer->is_company }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->birth_date }}</td>
                        <td>{{ $customer->is_active }}</td>
                        <td>{{ $customer->start_date }}</td>
                        <td>{{ $customer->end_date }}</td>
                        <td>{{ $customer->type }}</td>
                        <td>{{ $customer->price }}</td>
                        <td>{{ $customer->status }}</td>
                        <td><a href="{{ route('customer.edit', ['id' => $customer->id]) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>

    <script>
        $(document).ready(function() {
            $('.sort').click(function(e) {
                e.preventDefault();
                var column = $(this).data('column');
                var direction = 'asc';
                if ($(this).hasClass('sorted') && $(this).hasClass('asc')) {
                    direction = 'desc';
                }
                $('.sort').removeClass('sorted asc desc');
                $(this).addClass('sorted ' + direction);
                $.ajax({
                    url: '{{ route("customerGridView") }}',
                    type: 'GET',
                    data: {
                        search: $('#searchInput').val(),
                        sort: column,
                        direction: direction
                    },
                    success: function(response) {
                        $('#customerTable tbody').html($(response).find('#customerTable tbody').html());
                    }
                });
            });

            $('#searchForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("customerGridView") }}',
                    type: 'GET',
                    data: {
                        search: $('#searchInput').val()
                    },
                    success: function(response) {
                        $('#customerTable tbody').html($(response).find('#customerTable tbody').html());
                    }
                });
            });
        });
    </script>

</body>

</html>