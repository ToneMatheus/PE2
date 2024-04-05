<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Monthly Invoice</title>
</head>
<body>
    <div id="company">
        <h2>Company</h2>
        <p>Business Address</p>
        <p>City</p>
        <p>Country</p>
        <p>Postal</p>
    </div>

    <h1>Monthly Electrical Invoice</h1>

    <div id="user" class="row">
        <div class="col">
            @if($user->is_company == 1)
                <p>{{$user->company_name}}</p>
            @endif

            <p>{{$user->street}} {{$user->number}} {{$user->box}}</p>
            <p>{{$user->city}}</p>
            <p>{{$user->country}}</p>
            <p>{{$user->postal_code}}</p>
        </div>

        <div class="col">
            <p>Invoice: {{$invoice->id}}</p>
            <p>Date: {{$invoice->invoice_date}}</p>
            <p>Due Date: {{$invoice->due_date}}</p>
        </div>
    </div>

    <table>
        <tr>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Amount</th>
        </tr>

        @foreach($newInvoiceLines as $invoiceLine)
            <tr>
                <td>{{ $invoiceLine->type }}</td>
                <td>{{ $invoiceLine->unit_price }}</td>
                <td>{{ $invoiceLine->amount }}</td>
                <td>{{ $invoiceLine->unit_price * $invoiceLine->amount }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Total Amount: {{$invoice->total_amount}}</h2> 
</body>
</html>