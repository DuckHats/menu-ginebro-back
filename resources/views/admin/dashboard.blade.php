<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eaf4e2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #d1e8b2;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 10px 0;
        }

        .card a {
            text-decoration: none;
            color: #2d572c;
            font-weight: bold;
            font-size: 1.2rem;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #c0392b;
            color: #fff;
            font-size: 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #a93226;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard d'administrador</h2>

        <div class="card">
            <a href="/telescope">📊 Accedir a Telescope</a>
        </div>
        <div class="card">
            <a href="/">🏡 Tornar a la home</a>
        </div>

        <form method="GET" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
    </div>
</body>
</html>
