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
            <form action="{{ url('/customerGridView') }}" method="GET">
                <input type="text" name="search" required/>
                <button type="submit">Search</button>
            </form>
            <a href="{{ url('/customerGridView') }}" class="reset-button">Clear</a>
        </div>
        <table>
            <thead>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=id') }}">ID</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=last_name') }}">Last Name</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=first_name') }}">First Name</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=phone_nbr') }}">Phone Number</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=company_name') }}">Company Name</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=is_company') }}">Is Company</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=email') }}">Email</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=birth_date') }}">Birth Date</a></th>
                <th><a href="{{ url('/customerGridView?search=' . $searchQuery . '&sort=is_activate') }}">Is Activate</a></th>
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
                        <td><a href="{{ url("/customer/{$customer->id}/edit") }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
</body>
</html>