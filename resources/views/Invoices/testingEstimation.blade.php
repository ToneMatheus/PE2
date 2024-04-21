<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Estimation Test</title>
    <link rel="stylesheet" href="{{ asset('css/testingEstimation.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container">
        <form action="{{ route('CalculateEstimation')}}" method="POST">
            @csrf
                <div class="input-group">
                    <label for="customerID">Customer Nr.:</label>
                    <input type="number" id="customerID" name="customerID" value="1">
                    @error('customerID')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            <button type="submit">Generate Monthly Invoice</button>
        </form>
    </div>
</body>