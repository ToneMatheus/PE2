<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Warning: Open Invoice</title>
</head>
<body>
    <h1>Dear {{ $user_info->first_name }} {{ $user_info->last_name }},</h1>
    
    <p>
        If you are receiving this mail, it means that you have an open invoice from our company that was due 1 week ago. We are kindly 
        asking you to check if this is correct. If you indeed haven't paid this invoice yet, we ask you to urgently sort this out.
    </p>

    <p>
        If you cannot pay this invoice in <b>maximum 7 days from now</b>, we will be obliged to add a fee of {{ $fee }} euros 
        to your next invoice, as agreed to in your contract.
    </p>

    <p>
        These are the details of your invoice:
    </p>
    
    <table border="1">
        <tr>
            <th>
                Type
            </th>
            <th>
                Unit price
            </th>
            <th>
                Amount
            </th>
        </tr>

        @foreach ($invoice_info as $row)
        <tr>
            <td>{{ $row->type }}</td>
            <td>{{ $row->unit_price }}</td>
            <td>{{ $row->amount }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="2">Total</td>
            <td>{{ $total_amount }}</td>
        </tr>       


    </table>

    <p>
        If your payment has already been settled when you recieve this e-mail, you can ignore this reminder.
    </p>
    
    <p>
        Regards,<br>
        {{ $companyname }}
    </p>
</body>
</html>
