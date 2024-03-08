@section('topbar')
    <div class="flex justify-between items-center w-full p-4 shadow">
        <a class="text-blue-700 font-bold text-2xl" href="/dashboard">PE2</a>
        <div class="flex gap-4">
            @auth
            <ul class="flex justify-center items-center gap-4">
                <li>
                    <a href="/dashboard"
                        class="flex justify-center items-center cursor-pointer border-b-2 border-transparent hover:border-slate-200 p-2
                    {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li>
                    <a href="/invoices"
                        class="flex justify-center items-center cursor-pointer border-b-2 border-transparent hover:border-slate-200 p-2
                    {{ request()->is('invoices') ? 'active' : '' }}">Invoices</a>
                </li>
                <li>
                    <a href="/employees"
                        class="flex justify-center items-center cursor-pointer border-b-2 border-transparent hover:border-slate-200 p-2
                    {{ request()->is('employees') ? 'active' : '' }}">Employees</a>
                </li>
            </ul>
            
            <div class="flex justify-center items-center hover:opacity-80">
                <a href="/logout"><button class="w-full p-2 bg-blue-700 text-white cursor-pointer rounded">Logout</button></a>
            </div>
            @endauth
            @guest
                <div class="flex justify-center items-center hover:opacity-80">
                    <a href="/login"><button class="w-full p-2 bg-blue-700 text-white cursor-pointer rounded">Login</button></a>
                </div>
            @endguest
        </div>
    </div>
@endsection
