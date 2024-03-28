<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Users</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- If you want to use your custom CSS file, you can include it here -->
    <!-- <link rel="stylesheet" href="{{ asset('css/TestUserList.css') }}"> -->
</head>

<body class="bg-gray-100 p-8">
    <ul class="grid gap-4">
        @foreach ($users as $user)
            <li class="p-4 bg-white rounded-md shadow-md">
                <div class="text-lg font-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div class="text-gray-500">{{ $user->user_id }}</div>
                <form action="{{ route('TestUserList') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->user_id }}">
                    <button type="submit"
                        class="mt-4 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">
                        Add invoice line
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</body>

</html>