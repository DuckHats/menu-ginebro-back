<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dev Auth</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 20px }
        pre { background: #f6f6f6; padding: 12px }
        .msg { padding: 8px; margin-bottom: 12px }
        .success { background: #e6ffed; border-left: 4px solid #2ecc71 }
        .error { background: #ffecec; border-left: 4px solid #e74c3c }
    </style>
</head>
<body>
    <h1>Dev Auth - Cookie flow</h1>

    @if(session('success'))
        <div class="msg success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="msg error">{{ session('error') }}</div>
    @endif

    <p>Current user: <strong>{{ $user ? e($user->email) : 'not logged in' }}</strong></p>
    <p>Session cookie name: <code>{{ e($sessionName) }}</code></p>

    <h2>Actions</h2>
    <ul>
        <li><a href="/sanctum/csrf-cookie">Get CSRF cookie (/sanctum/csrf-cookie)</a></li>
        <li>
            <form method="post" action="/dev/auth/login">
                @csrf
                <label>Email: <input name="user" value="dev@example.com"></label>
                <label>Password: <input name="password" value="password123" type="password"></label>
                <button type="submit">Login</button>
            </form>
        </li>
        <li>
            <form method="post" action="/dev/auth/register">
                @csrf
                <label>Name: <input name="name" value="Dev"></label>
                <label>Last name: <input name="last_name" value="User"></label>
                <label>Email: <input name="email" value="dev@example.com"></label>
                <label>Password: <input name="password" value="password123" type="password"></label>
                <label>Confirm: <input name="password_confirmation" value="password123" type="password"></label>
                <input type="hidden" name="user_type_id" value="2">
                <button type="submit">Register</button>
            </form>
        </li>
        <li>
            <form method="post" action="/dev/auth/logout">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </li>
        <li>
            <form method="post" action="/dev/auth/logout-all">
                @csrf
                <button type="submit">Logout all sessions (by user)</button>
            </form>
        </li>
    </ul>

    <h2>Info</h2>
    <pre>Cookies: {!! e(json_encode($cookies)) !!}</pre>
</body>
</html>