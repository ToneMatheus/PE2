<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Invoice Extra Form</title>
    <link rel="stylesheet" href="{{ asset('css/EstimationGuest.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Add Invoice Extra Form</h1>

            <form id="addInvoiceExtraForm" action="{{ route('addInvoiceExtraForm')}}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="type">Type:</label>
                    <input type="text" id="type" name="type">
                </div>
                <div class="input-group">
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount">
                </div>
                <div class="input-group">
                    <label for="userID">User ID:</label>
                    <input type="number" id="userID" name="userID">
                </div>
                <button type="submit">Add extra invoice line</button>
            </form>
        </div>
    </div>
</body>