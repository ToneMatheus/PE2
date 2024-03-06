<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>invoice query</title>
    <link rel="stylesheet" href="{{ asset('css/querystyle.css') }}">
</head>
<body>
    <div class="wrapper">
        <h1>Invoice query</h1>

        <div class="floatingtable">
            <h2>all records from customerContract</h2>
        <table>
            <tr>
               <th>ID</th>
               <th>customerID</th>
               <th>startDate</th>
               <th>endDate</th>
               <th>type</th>
               <th>price</th>
               <th>status</th>
            </tr>
            @foreach ($resultsAll as $result)
            <tr>
               <td><b>{{ $result->ID }}</b></td>
               <td>{{ $result->customerID }}</td>
               <td>{{ $result->startDate }}</td>
               <td>{{ $result->endDate }}</td>
               <td>{{ $result->type }}</td>
               <td>{{ $result->price }}</td>
               <td>{{ $result->status }}</td>
            </tr>
            @endforeach
         </table>
        </div>

        <div class="floatingtable">
            <h2>records after query</h2>
            <table>
                <tr>
                    <th>contractID</th>
                    <th>customerID</th>
                    <th>startDate</th>
                    <th>endDate</th>
                    <th>type</th>
                    <th>price</th>
                    <th>contract status</th>
                    <th>invoiceID</th>
                    <th>invoice status</th>
                </tr>
                @foreach ($results1 as $result)
                <tr>
                   <td><b>{{ $result->cont_ID }}</b></td>
                   <td>{{ $result->customerID }}</td>
                   <td>{{ $result->startDate }}</td>
                   <td>{{ $result->endDate }}</td>
                   <td>{{ $result->type }}</td>
                   <td>{{ $result->price }}</td>
                   <td>{{ $result->cont_status }}</td>
                   <td>{{ $result->inv_ID }}</td>
                   <td>{{ $result->inv_status }}</td>
                </tr>
                @endforeach
             </table>
            </div>
    </div>
</body>
</html>