<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css') 
    <link href="/css/login.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <title>Eindwerk</title>
</head>
<body>
<div class="flex justify-center items-center flex-col">
        @yield('topbar')
        @show
    <div class="flex items-center w-full">
        @yield('content')
    </div>
</div>
</body>

</html>