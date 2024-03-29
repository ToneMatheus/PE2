<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Invoices</title>
    <link href="{{ asset('css/customerPortal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/portalNav.css') }}" rel="stylesheet">
</head>
<body>
    @include('Customers.WebPortalNav')
    <div class="invoice-content">
    <h1 class="invoice-title">Invoices</h1>
    <div class="content-wrapper">
    <form class="search-form" action="{{ route('customer.invoices', ['customerContractId' => $customerContractId]) }}" method="GET">
        <input type="text" name="search" value="{{ old('search') }}">
        <button type="submit">Search</button>
    </form>
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Amount</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->total_amount }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td style="background-color: {{ $invoice->status == 'paid' ? 'green' : ($invoice->status == 'pending' ? 'yellow' : ($invoice->status == 'sent' ? 'red' : 'white')) }}">{{ $invoice->status }}</td>
                    <td>{{ $invoice->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    {{ $invoices->links() }}
    </div>
    @include('Customers.WebPortalFooter')
</body>
</html>