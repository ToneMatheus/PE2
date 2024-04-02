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

        @foreach($months as $month)
            @php
                $paid = $newInvoiceLine->unit_price * ($estimation / 12);

                $discountRate = 0;
                foreach ($discounts as $discount) {
                    $startMonth = Carbon::parse($discount->start_date)->format('F');
                    $endMonth = Carbon::parse($discount->end_date)->format('F');

                    if ($month >= $startMonth && $month <= $endMonth) {
                        $discountRate = $discount->rate;
                        break;
                    }
                }

                $paid -= ($paid * $discountRate);
                $totalAmount += $paid;
            @endphp

            <tr>
                <td>{{ $month }}</td>
                <td>{{ round($estimation / 12, 2) }}</td>
                <td>{{ round(($estimation / 12) + ($consumption->consumption_value / 12), 2) }}</td>
                <td>{{ $discountRate }}</td>
                <td>{{ round($paid, 2) }}</td>
                <td>{{ round(($newInvoiceLine->amount / 12) - ($newInvoiceLine->amount / 12) * $discountRate, 2) }}</td>
            </tr>

            @php
                $mInvoices = collect($monthlyInvoices[$month] ?? [])->flatten();
            @endphp

            @foreach ($mInvoices as $mInvoiceLine)
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">{{ $mInvoiceLine->type }}</td>
                    <td>{{ $mInvoiceLine->amount }}</td>
                    
                    @if ($mInvoiceLine->type == 'Electricity')
                        <td>{{ $mInvoiceLine->amount - $paid }}</td>
                    @else
                        <td>&nbsp;</td> 
                    @endif
                </tr>
            @endforeach
        @endforeach

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
                <td>{{$meterReadings[1]->reading_value}}</td>
                <td>{{round($totalAmount, 2)}}</td>
                <td>{{round($newInvoiceLine->amount, 2)}}</td>
            </tr>
    </table>
    <h2>Total Amount: {{round($invoice->total_amount, 2)}}</h2> 

    @if($meterReadings[0] > $meterReadings[1])
        <p>Reduction on total amount of the next invoice.</p>
    @endif
</body>
</html>