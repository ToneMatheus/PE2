<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
    </head>
    <body>
        <h1>Welcome to our company!</h1>

        <p>Work email: {{ $employee->work_email }}</p>
        <p>Password: default</p>

        <p>When first logging in, the system will ask you to reset your password. For further questions, contact the database admin.</p>
        <a href="{{ route('dashboard') }}">Link to employee portal</a>
    </body>
</html>