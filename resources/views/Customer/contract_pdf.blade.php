<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pdf Template</title>
</head>
<body>
    <p>Hello {{ Auth::user()->username}},</p>
    
    <p>This is a custom pdf for your contract. Below are the details:</p>

    <ul>
        <li>Contract name: {{ $contract->product->product_name}}</li>
        <li>Description: {{ $contract->product->description}}</li>
        <li>Start date: {{ $contract->start_date }}</li>
        <ul>
            <li>Tarrif: {{ $contract->tarrif_id }}</li>
            <li>Price: ${{ $contract->customer_contract->price }}</li>
        </ul>
        <li>Type: {{ $contract->product->type }}</li>
        <li>Contract type: {{ $contract->customer_contract->type }}</li>
        <li>Status: {{ $contract->customer_contract->status }}</li>
        
    </ul>

    <p>Thank you for using our service!</p>
</body>
</html>