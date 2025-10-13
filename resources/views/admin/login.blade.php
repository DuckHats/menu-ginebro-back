<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent: {{ $accentColor }};
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen flex flex-col">

    <header class="p-6 border-b bg-white shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[color:var(--primary)]">{{ $appName }}</h1>
            <a href="/" class="text-sm font-medium text-white bg-[color:var(--accent)] px-4 py-2 rounded-full shadow hover:opacity-90 transition">
                Tornar a l'inici
            </a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-lg p-10 w-full max-w-md text-center border border-gray-100">
            <h2 class="text-3xl font-extrabold text-[color:var(--primary)] mb-6">Accés administrador</h2>

            @if($errors->any())
                <p class="text-red-500 text-sm mb-4">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                
                <div class="text-left">
                    <label for="text" class="block text-sm font-semibold text-gray-700">Usuari</label>
                    <input type="text" id="text" name="user" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
                </div>
                
                <div class="text-left">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Contrasenya</label>
                    <input type="password" id="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[color:var(--accent)] focus:outline-none">
                </div>
                
                <button type="submit"
                    class="w-full bg-[color:var(--accent)] text-white font-semibold py-2 rounded-lg shadow hover:opacity-90 transition">
                    Entrar
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-white border-t mt-10 text-center p-4 text-sm text-gray-500">
        {{ $footerText }}
    </footer>

</body>
</html>
