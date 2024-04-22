<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    
    <p>This is a custom email template for your invoice. Below are the details:</p>

    <ul>
        <li>Invoice type: {{ $invoice->type }}</li>
        <li>Price: ${{ $invoice->total_amount }}</li>
        <li>Status: {{ $invoice->status }}</li>
        <li>Due date: {{ $invoice->due_date }}</li>
    </ul>

    <p>Thank you for using our service!</p>
</body>
</html>