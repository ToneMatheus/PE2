<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <div class="login-container">
            <h2>Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                </div>

                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
    </body>
</html>