<?php $searchQuery = request('search'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Gridview</title>

        <link href="{{ asset('css/gridview.css') }}" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <div class="row">
            <form action="{{ route('customerGridView') }}" method="GET">
                <input type="text" name="search" required/>
                <button type="submit">Search</button>
            </form>
            <a href="{{ route('customerGridView') }}" class="reset-button">Clear</a>
        </div>
        <table>
            <thead>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'id', 'direction' => $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc']) }}">ID</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'last_name', 'direction' => $sort === 'last_name' && $direction === 'asc' ? 'desc' : 'asc']) }}">Last Name</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'first_name', 'direction' => $sort === 'first_name' && $direction === 'asc' ? 'desc' : 'asc']) }}">First Name</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'phone_nbr', 'direction' => $sort === 'phone_nbr' && $direction === 'asc' ? 'desc' : 'asc']) }}">Phone Number</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'company_name', 'direction' => $sort === 'company_name' && $direction === 'asc' ? 'desc' : 'asc']) }}">Company Name</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'is_company', 'direction' => $sort === 'is_company' && $direction === 'asc' ? 'desc' : 'asc']) }}">Is Company</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc']) }}">Email</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'birth_date', 'direction' => $sort === 'birth_date' && $direction === 'asc' ? 'desc' : 'asc']) }}">Birth Date</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'is_active', 'direction' => $sort === 'is_active' && $direction === 'asc' ? 'desc' : 'asc']) }}">Is Active</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'start_date', 'direction' => $sort === 'start_date' && $direction === 'asc' ? 'desc' : 'asc']) }}">Start Date</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'end_date', 'direction' => $sort === 'end_date' && $direction === 'asc' ? 'desc' : 'asc']) }}">End Date</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'type', 'direction' => $sort === 'type' && $direction === 'asc' ? 'desc' : 'asc']) }}">Type</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'price', 'direction' => $sort === 'price' && $direction === 'asc' ? 'desc' : 'asc']) }}">Price</a></th>
                <th><a href="{{ route('customerGridView', ['search' => $searchQuery, 'sort' => 'status', 'direction' => $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc']) }}">Status</a></th>       
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
                        <td>{{ $customer->is_activate }}</td>
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
</body>
</html>