<header class="bg-blue-500 text-white text-2xl p-5">
    <h1>Energy Company Web Portal</h1>
    <form method="POST" action="{{ route('customer.change-locale') }}">
    @csrf
    <div class="relative inline-block text-white">
        <select name="locale" onchange="this.form.submit()"class="block appearance-none w-full bg-blue-500 border border-blue-500 text-white py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-blue-500 focus:border-blue-700">
            <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
            <option value="nl" {{ App::getLocale() == 'nl' ? 'selected' : '' }}>Dutch</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.305 7.695a.999.999 0 0 1 1.414 0L10 11.075l3.282-3.38a.999.999 0 1 1 1.415 1.41l-3.988 4a.997.997 0 0 1-1.414 0l-3.988-4a.999.999 0 0 1 0-1.41z"/></svg>
        </div>
    </div>
    </form>
</header>
<nav class="bg-blue-700 text-white p-5">
    <ul class="flex space-x-4">
        <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
        <li><a href="{{ url('customer/invoiceStatus') }}" class="hover:underline">Invoice status</a></li>
        <li><a href="{{ url('/customer/consumption-history') }}" class="hover:underline">Consumption history</a></li>
        <li><a href="{{ url('/profile') }}" class="hover:underline">Profile</a></li>
    </ul>
</nav>