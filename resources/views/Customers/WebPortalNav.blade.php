<header class="bg-blue-500 text-white text-2xl p-5">
    <h1>Energy Company Web Portal</h1>
</header>
<nav class="bg-blue-700 text-white p-5">
    <ul class="flex space-x-4">
        <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
        <li><a href="{{ url('customer/invoiceStatus') }}" class="hover:underline">Invoice status</a></li>
        <li><a href="{{ url('/customer/consumption-history') }}" class="hover:underline">Consumption history</a></li>
        <li><a href="{{ url('/profile') }}" class="hover:underline">Profile</a></li>
    </ul>
</nav>