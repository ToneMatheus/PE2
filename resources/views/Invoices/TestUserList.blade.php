<x-app-layout>
    <body class="bg-gray-100 p-8">
        <div class="flex flex-wrap justify-center">
            @foreach ($users as $user)
                <div class="flex flex-col p-6 dark:bg-gray-800 rounded-md shadow-md m-4" style="width: 300px;">
                    <div class="text-white text-lg font-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="text-white">{{ $user->user_id }}</div>
                    <form action="{{ route('TestUserList') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->user_id }}">
                        <button type="submit"
                            class="mt-4 nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Add invoice line
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </body>
</x-app-layout>