
    <h1>Invoice Overview</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ $invoice->due_date }}</td>
                <td>{{ $invoice->total_amount }}</td>
                <td>{{ $invoice->status }}</td>
                <td>{{ $invoice->type }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    
