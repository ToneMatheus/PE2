<!DOCTYPE html>
<html>
<head>
    <title>Job Status</title>
</head>
<body>
    <h1>Regular Job Status</h1>
    <p>{{ session('regularJobStatus') }}</p>
    <form method="POST" action="{{ route('run-regular-job') }}">
        @csrf
        <button type="submit">Run Regular Job</button>
    </form>

    <h1>Special Job Status</h1>
    <p>{{ session('specialJobStatus') }}</p>
    <form method="POST" action="{{ route('run-special-job') }}">
        @csrf
        <button type="submit">Run Special Job</button>
    </form>
</body>
</html>