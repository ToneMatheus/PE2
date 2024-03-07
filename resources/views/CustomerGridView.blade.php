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
        <tr>
            <th><a href="{{ url('/customerGridView?sort=id') }}">ID</a></th>
            <th><a href="{{ url('/customerGridView?sort=lastName') }}">lastName</a></th>
            <th><a href="{{ url('/customerGridView?sort=firstName') }}">firstName</a></th>
            <th><a href="{{ url('/customerGridView?sort=phoneNumber') }}">phoneNumber</a></th>
            <th><a href="{{ url('/customerGridView?sort=companyName') }}">companyName</a></th>
            <th><a href="{{ url('/customerGridView?sort=isCompany') }}">isCompany</a></th>
            <th><a href="{{ url('/customerGridView?sort=userID') }}">userID</a></th>
            <th><a href="{{ url('/customerGridView?sort=startdate') }}">StartDate</a></th>
            <th><a href="{{ url('/customerGridView?sort=enddate') }}">EndDate</a></th>
            <th><a href="{{ url('/customerGridView?sort=type') }}">Type</a></th>
            <th><a href="{{ url('/customerGridView?sort=price') }}">Price</a></th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->lastName }}</td>
                <td>{{ $customer->firstName }}</td>
                <td>{{ $customer->phoneNumber }}</td>
                <td>{{ $customer->companyName }}</td>
                <td>{{ $customer->isCompany }}</td>
                <td>{{ $customer->userID }}</td>
                <td>{{ $customer->startdate }}</td>
                <td>{{ $customer->enddate }}</td>
                <td>{{ $customer->type }}</td>
                <td>{{ $customer->price }}</td>
                <td><a href="{{ url("/customer/{$customer->userID}/edit") }}">Edit</a></td>
            </tr>
        @endforeach
    </tbody>
    </table>
    {{ $customers->links() }}
    </div>
    </body>
</html>