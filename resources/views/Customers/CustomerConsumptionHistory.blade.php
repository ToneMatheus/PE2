<!DOCTYPE html>
<html>
<head>
    <title>Customer Consumption History</title>
    <link href="{{ asset('css/portalNav.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customerPortal.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    <script src="/js/consumptionChart.js"></script>
    <button onclick="fetchData('week')">Week</button>
    <button onclick="fetchData('month')">Month</button>
    <button onclick="fetchData('year')">Year</button>
    @include('Customers.WebPortalFooter')
</body>
</html>