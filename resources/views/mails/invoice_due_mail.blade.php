<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advance reminder: open invoice</title>
</head>
<body>
    <h1>Dear {{ $user_info->first_name }} {{ $user_info->last_name }},</h1>
    
    <p>
        We wanted to let you know that you have yet to pay on open invoice from our company. 
        The due date for the invoice in question was today. We understand that it is not always possible to manage invoices in time. 
        Therefore, as agreed to in our contract, we offer you <b>14 days to pay</b> the open invoice. If the invoice hasn't been paid for 
        yet after that, there will be a fee of {{ $fee }} euros added to your next invoice.
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
        Please log in to our website or scan the QR code in attachment to fill in payment.
    </p>

    <p>
        If your payment has already been settled when you recieve this e-mail, you can ignore this reminder.
    </p>
    
    <p>
        Regards,<br>
        {{ $companyname }}
    </p>
</body>
</html>
