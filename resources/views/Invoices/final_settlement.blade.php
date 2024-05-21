<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Settlement Mail</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    
    <p>Below are the details of your final settlement:</p>

    <ul>
        <li>Invoice type: {{ $invoice->type }}</li>
        <li>Price: ${{ $invoice->total_amount }}</li>
        <li>Status: {{ $invoice->status }}</li>
        <li>Due date: {{ $invoice->due_date }}</li>
    </ul>

    <p>
        If you are receiving this email, it means you're ending or switching contracts at our company. 
        If this is not the case and you are receiving this mail in error, please contact us on our website.
    </p>

    <p>Thank you for using our service!</p>
</body>
</html>