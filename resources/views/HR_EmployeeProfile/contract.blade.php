<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/contract.css" rel="stylesheet" type="text/css"/>
    <link href="/css/header.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Contract agreement</title>

</head>

<body class="body">
    <div class="container" style="margin-top: 30px; padding-bottom: 100px">
        <div class="content">
            @include('contractView')
        </div>
        <a style="margin-left: 900px;" href="{{route('downloadContract')}}"><button>Download pdf</button></a>
    </div>
</body>
</x-app-layout>