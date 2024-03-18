<!DOCTYPE html>
<html>
<head>
    <title>Customer Consumption History</title>
    <link href="{{ asset('css/portalNav.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customerPortal.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @include('Customers.WebPortalNav')

    <div class="content">
        <h1>Energy Consumption History</h1>
        <canvas id="consumptionChart"></canvas>
    </div>
    <script>
        var consumptionData = @json($consumptionData);
    </script>

    <script src="{{ asset('js/consumptionChart.js') }}"></script>

    @include('Customers.WebPortalFooter')
</body>
</html>