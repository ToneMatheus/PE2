<!DOCTYPE html>
<html>
<head>
    <title>Credit Note</title>
</head>
<body>
    <h2>Credit Note</h2>
    
    <p><strong>User Information:</strong></p>
    <ul>
        <li><strong>Username:</strong> {{ $user->username }}</li>
        <li><strong>Full Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Phone Number:</strong> {{ $user->phone_nbr }}</li>
        <li><strong>Birthdate:</strong> {{ $user->birth_date }}</li>
    </ul>
    
    <p><strong>Credit Note Details:</strong></p>
    <table border=1>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach($creditNoteLines as $line)
            <tr>
                <td>{{ $line->product }}</td>
                <td>{{ $line->quantity }}</td>
                <td>{{ $line->price }}</td>
                <td>{{ $line->amount }}</td>
                @php
                    $total += $line->amount;
                @endphp
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>TOTAL:</strong> {{ $total }} &euro;</p>
</body>
</html>