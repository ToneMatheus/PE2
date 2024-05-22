@php
    use Carbon\Carbon;
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    $totalAmount = 0;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Annual Invoice</title>
    <style>
        table{
            border-collapse: collapse;
        }

        table, th, td {
            border: solid 1px black;
        }
    </style>
</head>
<body>
    <div id="company">
        <h2>Company</h2>
        <p>Business Address</p>
        <p>City</p>
        <p>Country</p>
        <p>Postal</p>
    </div>

    <h1>Annual Electrical Invoice</h1>

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

    <hr/>

    <table>
        <tr>
            <th>Month</th>
            <th>Estimated Consumption</th>
            <th>Actual Consumption</th>
            <th>Discount Rate</th>
            <th>Paid</th>
            <th>Amount</th>
        </tr>

        @for ($i = $interval[0]; $i < $interval[1]; $i++)
            @php
                $paid = $newInvoiceLine->unit_price * ($estimation / ($interval[1] - $interval[0]));

                $discountRate = 0;
                foreach ($discounts as $discount) {
                    $startMonth = Carbon::parse($discount->start_date)->format('F');
                    $endMonth = Carbon::parse($discount->end_date)->format('F');

                    if ($months[$i-1] >= $startMonth && $months[$i-1] <= $endMonth) {
                        $discountRate = $discount->rate;
                        break;
                    }
                }

                $paid -= ($paid * $discountRate);
                $totalAmount += $paid;
            @endphp

            <tr>
                <td>{{ $months[$i-1] }}</td>
                <td>{{ round($estimation / ($interval[1] - $interval[0]), 2) }}</td>
                <td>{{ round(($estimation / ($interval[1] - $interval[0])) + ($consumption->consumption_value / ($interval[1] - $interval[0])), 2) }}</td>
                <td>{{ $discountRate }}</td>
                <td>{{ round($paid, 2) }}</td>
                <td>{{ round(($newInvoiceLine->amount / ($interval[1] - $interval[0])) - ($newInvoiceLine->amount / ($interval[1] - $interval[0])) * $discountRate, 2) }}</td>
            </tr>

            @foreach($monthlyInvoices[$months[$i-1]] ?? [] as $mInvoiceLine)
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">{{ $mInvoiceLine->type }}</td>
                    <td>{{ $mInvoiceLine->amount }}</td>
                    
                    @if ($mInvoiceLine->type == 'Electricity')
                        <td>{{ round($mInvoiceLine->amount - $paid, 2) }}</td>
                    @else
                        <td>&nbsp;</td> 
                    @endif
                </tr>
            @endforeach
        @endfor

    </table>

    <p></p>

    <table>
        <tr>
            <th>Year</th>
            <th>Estimated Consumption</th>
            <th>Actual Consumption</th>
            <th>Paid</th>
            <th>Amount</th>
        </tr>
        <tr>
                <td></td>
                <td>{{$estimation}}</td>
                <td>{{$meterReadings->reading_value}}</td>
                <td>{{round($totalAmount, 2)}}</td>
                <td>{{round($newInvoiceLine->amount, 2)}}</td>
            </tr>
    </table>
    <h2>Total Amount: {{round($invoice->total_amount, 2)}}</h2> 

    <p>
        @php
        echo DNS2D::getBarcodeHTML($domain . "/pay/" . $invoice->id . "/" . $hash, 'QRCODE',5,5);
        @endphp
    </p>

    <p><br/>Scanning this QR code will bring you directly to a page where you can handle the payment of your invoice.</p>

    <p>If you pay by bank transfer, mention: {{ $invoice->structured_communication }}</p>
</body>
</html>