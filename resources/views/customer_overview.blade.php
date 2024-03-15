<h1>Overview</h1>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($userData as $key => $user)
        <tr>
            <td>{{ $user->username }}</td>
            <td>{{ $customerData[$key]->lastName }}</td>
            <td>{{ $customerData[$key]->firstName }}</td>
            <td>{{ $addressData[$key]->address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>