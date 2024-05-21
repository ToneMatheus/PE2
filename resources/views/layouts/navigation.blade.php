@php
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Notifications\DatabaseNotification;
@endphp
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                @if(Auth::check())
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>

            {{-- Retrieve roles --}}
            <?php
                // $roleId = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                // $roleName = DB::table('roles')->where('id', $roleId)->first()->role_name;
            ?>

            <!-- Settings Dropdown -->
            @if(Auth::check())
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <a href="{{ route('faq') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 ml-3">
                            {{ __('FAQ') }}
                        </a>

                        {{-- Profile --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->username}}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
    
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
    
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
    
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <!-- Language Selector -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ strtoupper(App::getLocale()) }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <form method="POST" action="{{ route('customer.change-locale') }}">
                                @csrf
                                <x-dropdown-link>
                                    <button type="submit" name="locale" value="en" class="w-full text-left">{{ __('English') }}</button>
                                </x-dropdown-link>
                                <x-dropdown-link>
                                    <button type="submit" name="locale" value="nl" class="w-full text-left">{{ __('Dutch') }}</button>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                    
                    {{-- Notifications --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>&#128276;</div>
                            </button>
                        </x-slot>

                        {{-- @php
                            $role_id = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                            Log::info('Role ID: ' . $role_id);
                            // $notifications = auth()->user()->unreadNotifications->where('data.role_id', $role_id);
                            // Log::info('Notifications: ' . $notifications->count());

                            // $role_id = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                            $notifications = auth()->user()->unreadNotificationsForRole($role_id);
                            Log::info('Notifications: ' . $notifications->count());
                        @endphp
                        <x-slot name="content">
                            @if(auth()->user()->unreadNotifications->where('data.role_id', $role_id)->isEmpty())
                                <div class="px-4 py-2 text-sm text-gray-700 dark:text-white">
                                    No new notifications
                                </div>
                            @else
                                @foreach(auth()->user()->unreadNotifications->where('data.role_id', $role_id) as $notification)
                                    <x-dropdown-link :href="route('notification.read', $notification->id)" class="dark:text-white">
                                        {{ $notification->data['message'] }}
                                    </x-dropdown-link>
                                @endforeach
                            @endif
                        </x-slot> --}}

                        @php
                        
                            $role_id = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                            $notifications = DatabaseNotification::where('role_id', $role_id)
                                ->where('read_at', null)
                                ->where('data->role_id', $role_id)
                                ->get();
                        @endphp

                        <x-slot name="content">
                            <div class="max-h-96 overflow-auto">
                                @if($notifications->isEmpty())
                                    <div class="px-4 py-2 text-sm text-gray-700 dark:text-white">
                                        No new notifications
                                    </div>
                                @else
                                    @foreach($notifications as $notification)
                                        <x-dropdown-link :href="route('notification.read', $notification->id)" class="dark:text-white">
                                            {!! $notification->data['message'] !!}
                                        </x-dropdown-link>
                                    @endforeach
                                @endif
                            </div>
                        </x-slot>
                    </x-dropdown>   
                </div>
            @else
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <a href="{{ route('login') }}" class="ml-4 text-sm text-gray-700 dark:text-white underline">Login</a>
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-white underline">Register</a>
                <a href="{{ route('faq') }}" class="ml-4 text-sm text-gray-700 dark:text-white underline">FAQ</a>
            </div>
            @endif

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            {{-- <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div> --}}

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
