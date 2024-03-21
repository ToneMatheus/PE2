<?php
    session_start();
// function debug_to_console($data) 
    // {
    //     $output = $data;
    //     if (is_array($output))
    //         $output = implode(',', $output);
    
    //     echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    // }

?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/dashboardSlideshow.css') }}" rel="stylesheet">
    <script src="{{ asset('js/JQUERY.js') }}"></script>
    <script src="{{ asset('js/slideshow.js') }}"></script>
    <title>links</title>
</head>
<body>
    <header>
        
    </header>
    <h1>link page</h1>

    <ul>
        <li><a href="{{ url('/login') }}">Login</a></li>
        <li><a href="{{ url('/payslip') }}">Payslip</a></li>
        <li><a href="{{ url('/payList') }}">Paylist</a></li>
        <li><a href="{{ url('/contract') }}">Contract</a></li>
        <li><a href="{{ url('/profile') }}">Profile</a></li>
        <li><a href="{{ url('/holidayRequest') }}">Holiday Request</a></li>
    </ul>

    <div id="slideshow">
        <div><img src="{{ asset('images/texture2.jpg') }}" alt="" class="slideImage"></div>
        <div><img src="{{ asset('images/texture4.jpg') }}" alt="" class="slideImage"></div>
        <div><img src="{{ asset('images/texture3.jpg') }}" alt="" class="slideImage"></div>
        <div><img src="{{ asset('images/texture1.jpg') }}" alt="" class="slideImage"></div>
    </div>

    <?php
    ?>

</body>
</html>
