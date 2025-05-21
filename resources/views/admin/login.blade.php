<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">

    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eaf4e2;
            margin: 0;
            padding: 0;
            color: #2c3e50;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
            background-color: #4d857b;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
            color: #ffffff;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            margin-top: 15px;
            padding: 12px 20px;
            background-color: #ffffff;
            color: #4d857b;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #3d645d;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Accés administrador</h2>

        @if($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf  {{-- Protección contra CSRF --}}
            
            <label for="text">User:</label>
            <input type="text" id="text" name="user" required>
            
            <label for="password">Contrasenya:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
