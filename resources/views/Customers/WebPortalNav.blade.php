<header class="header-class">
    <h1 class="header-title">Energy Company Web Portal</h1>
</header>
<nav class="nav-class">
    <ul class="nav-list-class">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('customer/invoices/1') }}">Invoice status</a></li>
        <li><a href="{{ url('/customer/consumption-history') }}">Consumption history</a></li>
        <li><a href="{{ url('/profile') }}">Profile</a></li>
    </ul>
</nav>