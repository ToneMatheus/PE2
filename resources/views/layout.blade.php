<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: "Gill Sans", sans-serif;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h2 {
            margin: 0;
            color: #333;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .invoice-total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h2>Monthly Invoice</h2>
        </div>
        <div class="invoice-details">
            <p><strong>Invoice Number:</strong> INV-{{ $invoiceID }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Client:</strong> {{ $firstName }} {{ $lastName }}</p>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($Meter1))
                    <tr>
                        <td>Electricity Meter 1</td>
                        <td>{{ $Quantity1 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity1 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter2))
                    <tr>
                        <td>Electricity Meter 2</td>
                        <td>{{ $Quantity2 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity2 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter3))
                    <tr>
                        <td>Electricity Meter 3</td>
                        <td>{{ $Quantity3 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity3 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter4))
                    <tr>
                        <td>Electricity Meter 4</td>
                        <td>{{ $Quantity4 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity4 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter5))
                    <tr>
                        <td>Electricity Meter 5</td>
                        <td>{{ $Quantity5 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity5 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter6))
                    <tr>
                        <td>Electricity Meter 6</td>
                        <td>{{ $Quantity6 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity6 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter7))
                    <tr>
                        <td>Electricity Meter 7</td>
                        <td>{{ $Quantity7 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity7 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter8))
                    <tr>
                        <td>Electricity Meter 8</td>
                        <td>{{ $Quantity8 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity8 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter9))
                    <tr>
                        <td>Electricity Meter 9</td>
                        <td>{{ $Quantity9 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity9 * $unitPrice }}</td>
                    </tr>
                @endif
                @if (isset($Meter10))
                    <tr>
                        <td>Electricity Meter 10</td>
                        <td>{{ $Quantity10 }} kWh</td>
                        <td>€ {{ $unitPrice }}</td>
                        <td>€ {{ $Quantity10 * $unitPrice }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Basic Service Fee</td>
                    <td>1</td>
                    <td>€ 10.00</td>
                    <td>€ 10.00</td>
                </tr>
                <tr>
                    <td>Distribution Fee</td>
                    <td>1</td>
                    <td>€ 10.00</td>
                    <td>€ 10.00</td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-total">
            <p><strong>Total:</strong>€ {{ $totalPrice }}</p>
        </div>
    </div>
</body>
</html>