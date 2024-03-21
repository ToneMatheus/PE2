<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advance reminder: open invoice</title>
</head>
<body>
    <h1>Dear {{ $user_info[0]->first_name }} {{ $user_info[0]->last_name }},</h1>
    
    <p>
        We wanted to let you know that you have an unpaid invoice from our company. 
        We at {{ $companyname }} understand that managing expenses can be challenging, 
        and we want to ensure that you have ample time to make arrangements for your payment. 
        Therefore, we are sending you a reminder that you have <b>7 days</b> before your invoice is due. 
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

        @foreach ($total_amount as $r)
        <tr>
            <td colspan="2">Total</td>
            <td>{{ $r->total_amount }}</td>
        </tr>       
        @endforeach


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
