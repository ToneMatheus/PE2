<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>unpaid invoice query</title>
    <link rel="stylesheet" href="{{ asset('css/querystyle.css') }}">
</head>
<body>
    <div class="wrapper">
        <h1>Unpaid invoice query</h1>

        <div class="floatingtable">
            <h2>all records from invoices</h2>
        <table>
            <tr>
               <th>invoice ID</th>
               <th>invoice date</th>
               <th>due date</th>
               <th>total amount</th>
               <th>status</th>
            </tr>
            @foreach ($resultsAll as $result)
            <tr>
               <td><b>{{ $result->id }}</b></td>
               <td>{{ $result->invoice_date }}</td>
               <td>{{ $result->due_date }}</td>
               <td>{{ $result->total_amount }}</td>
               <td>{{ $result->status }}</td>
            </tr>
            @endforeach
         </table>
        </div>

        <div class="floatingtable">
            <h2>records after query</h2>
            <table>
                <tr>
                    <th>invoice ID</th>
                    <th>invoice date</th>
                    <th>due date</th>
                    <th>total amount</th>
                    <th>status</th>
                </tr>
                @foreach ($results1 as $result)
                <tr>
                   <td><b>{{ $result->id }}</b></td>
                   <td>{{ $result->invoice_date }}</td>
                   <td>{{ $result->due_date }}</td>
                   <td>{{ $result->total_amount }}</td>
                   <td>{{ $result->status }}</td>
                </tr>
                @endforeach
             </table>
            </div>
    </div>
</body>
</html>