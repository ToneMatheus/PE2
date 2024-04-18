<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/table-sort.js') }}"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-gray-800 py-4 flex justify-between items-center">
        <div class="container mx-auto px-4">
            <a href="#" class="text-white text-lg font-semibold">Energy</a>
        </div>
        <form method="POST" action="{{ route('customer.change-locale') }}" class="mr-4">
            @csrf
            <div class="relative inline-block text-white">
                <select name="locale" onchange="this.form.submit()" class="block appearance-none w-full bg-gray-800 border border-gray-700 text-white py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-gray-700 focus:border-gray-600">
                    <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="nl" {{ App::getLocale() == 'nl' ? 'selected' : '' }}>Dutch</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.305 7.695a.999.999 0 0 1 1.414 0L10 11.075l3.282-3.38a.999.999 0 1 1 1.415 1.41l-3.988 4a.997.997 0 0 1-1.414 0l-3.988-4a.999.999 0 0 1 0-1.41z"/></svg>
                </div>
            </div>
        </form>
    </nav>
